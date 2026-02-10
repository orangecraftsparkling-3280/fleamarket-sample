<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use Stripe\Checkout\Session;

class PurchaseFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_process_and_address_integration()
    {
        \Mockery::mock('alias:' . Session::class)
            ->shouldReceive('create')
            ->andReturn((object)[
                'id' => 'test_session_id',
                'url' => 'http://localhost/mock-stripe-checkout'
            ]);

        /** @var \App\Models\User $user */
        $user = User::factory()->create(['name' => 'テストユーザー', 'email_verified_at' => now()]);

        Profile::factory()->create([
            'user_id' => $user->id,
            'post_code' => '123-4567',
            'address' => '初期住所'
        ]);

        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'price' => 1000,
            'is_sold' => false
        ]);

        $response = $this->actingAs($user)->get(route('purchase', ['item_id' => $item->id]) . '?payment_method=konbini');
        $response->assertStatus(200);
        $response->assertSee('コンビニ払い');

        $newAddress = [
            'name'      => $user->name,
            'post_code' => '888-8888',
            'address'   => '秋田県大仙市',
            'building'  => '大曲ビル'
        ];

        $this->actingAs($user)->post(route('profile.update'), $newAddress);

        $user->refresh();

        $purchasePage = $this->actingAs($user)->get(route('purchase', ['item_id' => $item->id]));
        $purchasePage->assertSee('888-8888');
        $purchasePage->assertSee('秋田県大仙市');

        $this->actingAs($user)->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'card',
            'address' => '秋田県大仙市',
        ])->assertStatus(303);

        $this->actingAs($user)->get(route('purchase.success', ['item_id' => $item->id]));

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'is_sold' => true,
        ]);

        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->get('/mypage?tab=buy')->assertSee('テスト商品');
    }
}
