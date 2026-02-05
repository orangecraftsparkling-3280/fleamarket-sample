<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class SearchFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_search_items_by_name_partial_match()
    {
        Item::factory()->create(['name' => 'サウナハット']);
        Item::factory()->create(['name' => 'サウナマット']);
        Item::factory()->create(['name' => 'スニーカー']);

        $response = $this->get('/?keyword=サウナ');

        $response->assertStatus(200);
        $response->assertSee('サウナハット');
        $response->assertSee('サウナマット');
        $response->assertDontSee('スニーカー');
    }


    public function test_search_keyword_is_maintained_in_mylist_tab()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $item = Item::factory()->create(['name' => 'ヴィンテージジーンズ']);
        $user->favoriteItems()->attach($item->id);

        $response = $this->actingAs($user)->get('/?keyword=ジーンズ&tab=mylist');
        $response->assertStatus(200);
        $response->assertSee('ヴィンテージジーンズ');
        $response->assertSee('value="ジーンズ"', false);
    }
}
