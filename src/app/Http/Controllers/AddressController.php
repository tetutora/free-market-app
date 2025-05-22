<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Http\Requests\AddressRequest;

class AddressController extends Controller
{
    public function create()
    {
        return view('profile.create');
    }

    public function edit(Address $address)
    {
        $user = auth()->user();
        return view('profile.create', compact('address', 'user'));
    }

    public function store(AddressRequest $request)
    {
        Address::createForUser($request->validated(), auth()->id());

        return redirect()->route('profile.edit')->with('success', '住所を追加しました。');
    }

    public function update(AddressRequest $request, Address $address)
    {
        $address->updateForUser($request->validated(), auth()->id());

        return redirect()->route('profile.edit')->with('success', '住所を更新しました。');
    }

    public function destroy(Address $address)
    {
        $address->deleteForUser(auth()->id());

        return redirect()->route('profile.edit')->with('success', '住所を削除しました。');
    }
}
