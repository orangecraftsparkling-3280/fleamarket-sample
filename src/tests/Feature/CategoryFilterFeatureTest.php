<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Item;

class CategoryFilterFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_filter_items_by_category()
    {
        $fashion = Category::create(['name' => 'ファッション']);
        $electronics = Category::create(['name' => '家電']);

        $fashionItem = Item::factory()->create(['name' => 'シャツ']);
        $fashionItem->categories()->attach($fashion->id);

        $electronicsItem = Item::factory()->create(['name' => '掃除機']);
        $electronicsItem->categories()->attach($electronics->id);

        $response = $this->get('/?category=' . $fashion->id);

        $response->assertStatus(200);
        $response->assertSee('シャツ');
        $response->assertDontSee('掃除機');
    }

    public function test_all_items_are_shown_without_category_filter()
    {
        $category = Category::create(['name' => 'ファッション']);
        $item = Item::factory()->create(['name' => 'シャツ']);
        $item->categories()->attach($category->id);
        Item::factory()->create(['name' => '掃除機']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('シャツ');
        $response->assertSee('掃除機');
    }
}
