<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    // 住所追加フォーム
    public function create()
    {
        return view('profile.create');
    }

    // 住所編集フォーム
    public function edit(Address $address)
    {
        $user = auth()->user();
        return view('profile.create', compact('address', 'user'));
    }

    // 住所保存
    public function store(Request $request)
    {
        $request->validate([
            'postal_code' => 'required|string',
            'prefecture' => 'required|string',
            'city' => 'required|string',
            'street' => 'required|string',
            'building' => 'nullable|string',
        ]);

        $address = new Address();
        $address->user_id = auth()->id();
        $address->postal_code = $request->postal_code;
        $address->prefecture = $request->prefecture;
        $address->city = $request->city;
        $address->street = $request->street;
        $address->building = $request->building;
        $address->save();

        return redirect()->route('profile.edit')->with('success', '住所を追加しました。');
    }

    // 住所更新
    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'postal_code' => 'required|string',
            'prefecture' => 'required|string',
            'city' => 'required|string',
            'street' => 'required|string',
            'building' => 'nullable|string',
        ]);

        $address->update($request->only('postal_code', 'prefecture', 'city', 'street', 'building'));

        return redirect()->route('profile.edit')->with('success', '住所を更新しました。');
    }

    // 住所削除
    public function destroy(Address $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $address->delete();

        return redirect()->route('profile.edit')->with('success', '住所を削除しました。');
    }
}
