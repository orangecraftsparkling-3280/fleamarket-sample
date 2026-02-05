<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class MyListFeatureTest extends TestCase
{
    use RefreshDatabase;


    public function test_only_liked_items_are_displayed()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $likedItem = Item::factory()->create(['name' => 'いいねした商品']);
        $notLikedItem = Item::factory()->create(['name' => 'いいねしていない商品']);

        $user->favoriteItems()->attach($likedItem->id);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしていない商品');
    }

    public function test_sold_label_is_displayed_on_purchased_items_in_mylist()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $soldItem = Item::factory()->create([
            'name' => '売却済みのお気に入り',
            'is_sold' => true
        ]);
        $user->favoriteItems()->attach($soldItem->id);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertSee('売却済みのお気に入り');
        $response->assertSee('sold');
    }

    public function test_nothing_is_displayed_when_not_authenticated()
    {
        Item::factory()->create(['name' => '誰かの商品']);

        $response = $this->get('/?tab=mylist');

        $response->assertDontSee('誰かの商品');
    }
}
