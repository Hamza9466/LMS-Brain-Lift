<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\AnnouncementUser;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $uid = auth()->id();

        $rows = AnnouncementUser::with('announcement')
            ->where('user_id', $uid)
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.pages.announcements.student-index', compact('rows'));
    }

    public function markRead($announcementId)
    {
        $row = AnnouncementUser::where('announcement_id', $announcementId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (!$row->read_at) {
            $row->read_at = now();
            $row->save();
        }

        return back()->with('success','Marked as read.');
    }
}