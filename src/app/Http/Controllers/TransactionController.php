<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function show(Purchase $purchase)
    {
        $purchase->load('product', 'product.images');

        return view('transactions.show', compact('purchase'));
    }

    public function updateStatus(Request $request, Purchase $purchase)
{
    $this->authorize('update', $purchase); // ポリシーで制御推奨

    $newStatus = $request->input('status');
    $validTransitions = [
        'paid' => 'shipped',
        'shipped' => 'received',
    ];

    if (isset($validTransitions[$purchase->status]) && $validTransitions[$purchase->status] === $newStatus) {
        $purchase->status = $newStatus;

        if ($newStatus === 'received') {
            $purchase->status = 'completed'; // 自動で completed にするならここで
        }

        $purchase->save();
    }

    return redirect()->route('transactions.show', $purchase->id);
}

}
