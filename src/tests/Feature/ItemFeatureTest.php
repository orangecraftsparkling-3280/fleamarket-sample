<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class ItemFeatureTest extends TestCase
{
    use RefreshDatabase;


    public function test_can_get_all_items()
    {
        Item::factory()->count(2)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $this->assertEquals(2, Item::count());
    }


    public function test_sold_label_is_displayed_on_purchased_items()
    {
        Item::factory()->create([
            'name' => '売却済み商品',
            'is_sold' => true
        ]);

        $response = $this->get('/');

        $response->assertSee('sold');
    }

    public function test_own_items_are_not_displayed_in_list()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $ownItem = Item::factory()->create([
            'name' => '私の出品物',
            'user_id' => $user->id
        ]);

        $otherItem = Item::factory()->create([
            'name' => '誰かの出品物'
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertSee('誰かの出品物');
        $response->assertDontSee('私の出品物');
    }
}
