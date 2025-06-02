<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session as StripeSession;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PurchaseController extends Controller
{
    public function create(Product $product)
    {
        if (!$product->is_listed) {
            return redirect()->route('products.index')
                ->with('error', 'この商品は購入できません。');
        }

        $addresses = Auth::user()->addresses;

        return view('purchases.create', compact('product', 'addresses'));
    }

    public function payment(PurchaseRequest $request, Product $product)
    {
        $method = $request->payment_method;

        if ($method === 'card') {
            return $this->checkout($request, $product);
        }

        if ($method === 'konbini') {
            return $this->konbiniPayment($request, $product);
        }

        return back()->with('error', '支払い方法が正しくありません。');
    }

    public function checkout(PurchaseRequest $request, Product $product)
    {
        $this->setStripeKey();

        $session = $product->createCheckoutSession(Auth::user(), $request->address_id);

        return redirect($session->url);
    }

    private function konbiniPayment(PurchaseRequest $request, Product $product)
    {
        $this->setStripeKey();

        $intent = $product->createKonbiniPaymentIntent(Auth::user(), $request->address_id);

        $product->markAsSold();

        return redirect()->route('receipt.show', ['id' => $intent->id]);
    }

    public function success(Request $request)
    {
        $this->setStripeKey();

        $session = StripeSession::retrieve($request->get('session_id'));
        $metadata = $session->metadata ?? null;
        $product = Product::find($metadata->product_id ?? null);

        if (!$product || $session->payment_status !== 'paid') {
            return redirect()->route('products.index')
                ->with('error', '購入処理に失敗しました。');
        }

        Purchase::createFromStripeData($metadata, $session->amount_total / 100, 'card', 'paid');
        $product->markAsSold();

        return redirect()->route('mypage')
            ->with('success', '購入が完了しました。');
    }

    public function receipt($id)
    {
        $this->setStripeKey();

        $intent = PaymentIntent::retrieve($id, [
            'expand' => [
                'next_action.konbini_display_details',
                'charges.data.payment_method_details.konbini',
            ],
        ]);

        $product = Product::find($intent->metadata->product_id ?? null);

        return view('purchases.receipt', compact('intent', 'product'));
    }

    private function setStripeKey(): void
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }
}