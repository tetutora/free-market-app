<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function toggleFollow(User $user)
    {
        $authUser = auth()->user();

        [$success, $message] = Follow::toggle($authUser, $user);

        $statusType = $success ? 'status' : 'error';

        return redirect()->back()->with($statusType, $message);
    }
}
