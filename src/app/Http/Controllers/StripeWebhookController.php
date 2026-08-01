<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                config('services.stripe.webhook_secret')
            );
        } catch (UnexpectedValueException | SignatureVerificationException $e) {
            Log::warning('Stripe webhook signature verification failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'invalid signature'], 400);
        }

        $session = $event->data->object;

        if ($event->type === 'checkout.session.async_payment_succeeded'
            || ($event->type === 'checkout.session.completed' && $session->payment_status === 'paid')
        ) {
            $this->fulfillCheckout($session);
        }

        return response()->json(['status' => 'ok']);
    }

    private function fulfillCheckout($session): void
    {
        $metadata = $session->metadata;

        app('db')->transaction(function () use ($metadata) {
            $item = Item::lockForUpdate()->find($metadata->item_id);

            if (!$item || $item->is_sold) {
                return;
            }

            $item->purchase()->create([
                'user_id'   => $metadata->user_id,
                'post_code' => $metadata->post_code,
                'address'   => $metadata->address,
                'building'  => $metadata->building ?: null,
            ]);

            $item->update(['is_sold' => true]);
        });
    }
}
