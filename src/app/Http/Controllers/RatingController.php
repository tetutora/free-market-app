<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Rating;
use App\Models\Purchase;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $purchase = Purchase::with('product')->findOrFail($request->purchase_id);
        $isSeller = $purchase->product->user_id === Auth::id() ? 1 : 0;

//         dd([
//     'auth_id' => Auth::id(),
//     'purchase_id' => $purchase->product->user_id,
//     'is_seller' => $isSeller, // ✅ `is_seller` を `int` にキャストした値を確認
// ]);

        $existingRating = Rating::where('purchase_id', $purchase->id)
                                ->where('user_id', Auth::id())
                                ->count();

        if ($existingRating > 0) {
            return redirect()->route('transactions.show', $purchase->id)
                            ->with('error', 'この商品にはすでに評価しています。');
        }

        Rating::create([
    'purchase_id' => $purchase->id,
    'user_id' => Auth::id(),
    'rating' => $request->rating,
    'is_seller' => ($purchase->product->user_id === Auth::id()) ? 1 : 0, // ✅ 明示的に `1` または `0` をセット
    'comment' => $request->comment,
]);



        $sellerRated = Rating::where('purchase_id', $purchase->id)->where('is_seller', 1)->count();
        $buyerRated = Rating::where('purchase_id', $purchase->id)->where('is_seller', 0)->count();

        if ($sellerRated > 0 && $buyerRated > 0) {
            $purchase->update(['status' => 'completed']);
        } else {
            $purchase->update(['status' => 'received']);
        }

        return redirect()->route('transactions.show', $purchase->id)->with('success', '評価を送信しました！');
    }
}
