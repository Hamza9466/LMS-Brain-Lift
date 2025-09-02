<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $uid = auth()->id();

        $notifications = UserNotification::where('user_id', $uid)
            ->orderByDesc('created_at')
            ->paginate(30);

        return view('admin.pages.notifications.index', compact('notifications'));
    }

    public function markRead($id)
    {
        $n = UserNotification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (!$n->read_at) {
            $n->read_at = now();
            $n->save();
        }
        return back()->with('success','Notification marked read.');
    }

   public function markAllRead()
{
    UserNotification::where('user_id', auth()->id())
        ->whereNull('read_at')
        ->update(['read_at' => now()]);

    return back()->with('success','All notifications marked read.');
}
}