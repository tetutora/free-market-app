<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Follow;
use App\Models\History;
use App\Models\Notification;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $data = $user->getMypageData();

        return view('mypage.index', array_merge(['user' => $user], $data));
    }
}
