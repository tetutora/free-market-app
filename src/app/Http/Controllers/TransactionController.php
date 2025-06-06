<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function show(Purchase $purchase)
    {
        $purchase->load('product', 'product.images', 'ratings');

        $userId = Auth::id();
        $userHasRated = $purchase->ratings()->where('user_id', $userId)->exists();

        return view('transactions.show', compact('purchase', 'userHasRated'));
    }

    public function updateStatus(Request $request, Purchase $purchase)
    {
        $newStatus = $request->input('status');
        $user = Auth::user();

        if ($newStatus === 'shipped') {
            if ($user->id !== $purchase->product->user_id) {
                abort(403, '出品者のみ発送済みに変更できます。');
            }
            if ($purchase->status !== 'paid') {
                return redirect()->back()->with('error', '現在のステータスから発送済みには変更できません。');
            }
            $purchase->status = 'shipped';
        } elseif ($newStatus === 'received') {
            if ($user->id !== $purchase->user_id) {
                abort(403, '購入者のみ受け取り変更にできます。');
            }
            if ($purchase->status !== 'shipped') {
                return redirect()->back()->with('error', '現在のステータスから受け取り済みには変更できません。');
            }
            $purchase->status = 'received';

            if ($purchase->buyer_reviewed && $purchase->seller_reviewed) {
                $purchase->status = 'completed';
            }
        } elseif ($newStatus === 'paid') {
            if ($user->id !== $purchase->user_id) {
                abort(403, '購入者のみ支払い完了に変更できます。');
            }
            if ($purchase->status !== 'purchased') {
                return redirect()->back()->with('error', '現在のステータスから支払い済みに変更できません。');
            }
            $purchase->status = 'paid';
        } else {
            return redirect()->back()->with('error', '無効なステータス変更です。');
        }

        $purchase->save();

        return redirect()->route('transactions.show', $purchase->id)->with('success', 'ステータスが更新されました。');
    }
}
