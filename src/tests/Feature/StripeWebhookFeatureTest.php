<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Stripe\Webhook;

class StripeWebhookFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_invalid_signature_is_rejected()
    {
        \Mockery::mock('alias:' . Webhook::class)
            ->shouldReceive('constructEvent')
            ->andThrow(new \UnexpectedValueException('invalid payload'));

        $this->postJson('/stripe/webhook', [], ['Stripe-Signature' => 'bad'])
            ->assertStatus(400);
    }

    public function test_already_sold_item_is_not_purchased_twice()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create(['is_sold' => true]);

        \Mockery::mock('alias:' . Webhook::class)
            ->shouldReceive('constructEvent')
            ->andReturn((object)[
                'type' => 'checkout.session.completed',
                'data' => (object)[
                    'object' => (object)[
                        'payment_status' => 'paid',
                        'metadata' => (object)[
                            'user_id'   => (string) $user->id,
                            'item_id'   => (string) $item->id,
                            'post_code' => '000-0000',
                            'address'   => 'テスト住所',
                            'building'  => '',
                        ],
                    ],
                ],
            ]);

        $this->postJson('/stripe/webhook', [], ['Stripe-Signature' => 'test'])
            ->assertStatus(200);

        $this->assertDatabaseMissing('purchases', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
    }
}
