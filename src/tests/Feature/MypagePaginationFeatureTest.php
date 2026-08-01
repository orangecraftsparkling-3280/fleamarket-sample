<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class MypagePaginationFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_sell_tab_is_paginated_at_twelve_per_page()
    {
        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        Item::factory()->count(13)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/mypage?tab=sell');

        $response->assertStatus(200);
        $response->assertViewHas('items', function ($items) {
            return $items->count() === 12 && $items->hasMorePages();
        });

        $secondPage = $this->actingAs($user)->get('/mypage?tab=sell&page=2');
        $secondPage->assertViewHas('items', function ($items) {
            return $items->count() === 1;
        });
    }

    public function test_buy_tab_is_paginated_at_twelve_per_page()
    {
        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);

        foreach (range(1, 13) as $i) {
            $item = Item::factory()->create(['is_sold' => true]);
            $user->boughtItems()->attach($item->id, [
                'post_code' => '000-0000',
                'address' => 'テスト住所',
                'building' => null,
            ]);
        }

        $response = $this->actingAs($user)->get('/mypage?tab=buy');

        $response->assertStatus(200);
        $response->assertViewHas('items', function ($items) {
            return $items->count() === 12 && $items->hasMorePages();
        });

        $secondPage = $this->actingAs($user)->get('/mypage?tab=buy&page=2');
        $secondPage->assertViewHas('items', function ($items) {
            return $items->count() === 1;
        });
    }
}
