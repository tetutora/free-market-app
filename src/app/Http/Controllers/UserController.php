<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function show(User $user)
    {
        $products = $user->products()->where('is_listed', true)->get();

        return view('mypage.user-profile', compact('user', 'products'));
    }
}
