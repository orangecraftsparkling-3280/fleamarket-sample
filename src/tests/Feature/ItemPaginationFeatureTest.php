<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;

class ItemPaginationFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_list_is_paginated_at_twelve_per_page()
    {
        Item::factory()->count(13)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('items', function ($items) {
            return $items->count() === 12 && $items->hasMorePages();
        });

        $secondPage = $this->get('/?page=2');
        $secondPage->assertStatus(200);
        $secondPage->assertViewHas('items', function ($items) {
            return $items->count() === 1;
        });
    }
}
