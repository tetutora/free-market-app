<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        $user = Auth::user();

        if ($notification->user_id !== $user->id) {
            abort(403);
        }

        if (is_null($notification->read_at)) {
            $notification->update(['read_at' => now()]);
        }

        return redirect()->route('notifications.index');
    }
}
