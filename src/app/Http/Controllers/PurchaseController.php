<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function create(Product $product)
    {
        if (!$product->is_listed) {
            return redirect()->route('products.index')->with('error', 'この商品は購入できません。');
        }

        return view('purchases.create', compact('product'));
    }

    public function store(Request $request,  Product $product)
    {
        if (!$product->is_listed) {
            return redirect()->route('products.index')->with('error', 'この商品はすでに売り切れています。');
        }

        $product->update([
            'is_listed' => false,
        ]);

        return redirect()->route('products.show', $product)->with('success', '購入が完了しました。');
    }
}
