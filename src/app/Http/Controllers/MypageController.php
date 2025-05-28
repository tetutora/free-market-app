<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Follow;
use App\Models\History;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $unreadCount = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        $favorites = Favorite::with('product')->where('user_id', $user->id)->get();
        $purchases = $user->purchases()->with('product')->get();
        $products = $user->products()->get();

        $inTransactions = $user->purchases()
            ->whereIn('status', ['purchased', 'paid', 'shipped', 'received'])
            ->with('product')
            ->get();

        $histories = $user->viewHistories()
            ->with('product')
            ->orderByDesc('viewed_at')
            ->take(10)
            ->get();

        $followings = $user->followings()->get();

        return view('mypage.index', compact(
            'user', 'favorites', 'purchases', 'products',
            'inTransactions', 'histories', 'unreadCount', 'followings'
        ));
    }
}