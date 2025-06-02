<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\PaymentIntent;

class WebhookController extends Controller
{
    public function handle(Request $request)
{
    Stripe::setApiKey(config('services.stripe.secret'));

    $payload = $request->getContent();
    $sigHeader = $request->header('Stripe-Signature');
    $endpointSecret = config('services.stripe.webhook_secret');

    try {
        $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
    } catch (\Exception $e) {
        Log::error('Stripe webhook error: ' . $e->getMessage());
        return response('Invalid payload', 400);
    }

    if ($event->type === 'payment_intent.succeeded') {
    $paymentIntent = $event->data->object;

    // ここは配列としてアクセス
    $purchaseId = $paymentIntent->metadata['purchase_id'] ?? null;

    Log::info('PaymentIntent metadata purchase_id: ' . ($purchaseId ?? 'null'));
    Log::info('Full PaymentIntent metadata:', (array)$paymentIntent->metadata);

    if ($purchaseId) {
        $purchase = Purchase::find($purchaseId);
        if ($purchase && $purchase->status === 'purchased') {
            $purchase->status = 'paid';
            $purchase->save();
            Log::info("Purchase ID {$purchaseId} marked as paid.");
        }
    }
}
    return response('Webhook handled', 200);
}
}