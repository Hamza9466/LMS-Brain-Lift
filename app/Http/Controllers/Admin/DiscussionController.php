<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscussionThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DiscussionController extends Controller
{
  // app/Http/Controllers/Admin/DiscussionController.php



public function index(Request $request)
{
    $user   = Auth::user();
    $filter = $request->query('filter'); // 'replied' | 'unreplied' | null

    // mark seen for badge
    Cache::put("disc_seen_at_{$user->id}", now(), 60 * 60 * 24 * 30);

    // base scope
    $base = DiscussionThread::query()
        ->with(['lesson','course','user'])
        ->withCount('replies');

    // Teacher scope: only their courses (NO pivot table)
    if ($user->role === 'teacher') {
        $base->whereHas('course', function ($q) use ($user) {
            $q->where('teacher_id', $user->id);
        });
    }

    // counts using same base
    $counts = [
        'all'       => (clone $base)->count(),
        'replied'   => (clone $base)->has('replies')->count(),
        'unreplied' => (clone $base)->doesntHave('replies')->count(),
    ];

    // apply filter
    if ($filter === 'replied') {
        $base->has('replies');
    } elseif ($filter === 'unreplied') {
        $base->doesntHave('replies');
    }

    $threads = $base->latest()->paginate(20);

   return view('admin.pages.Q&A.discussion-index', [
    'threads' => $threads,
    'counts'  => $counts,
    'filter'  => $filter,
    'course'  => null,   // ensure defined
    'lesson'  => null,   // ensure defined
]);
}


    public function pin(DiscussionThread $thread)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin','teacher'])) abort(403);

        $thread->is_pinned = !$thread->is_pinned;
        $thread->save();

        return back()->with('success', $thread->is_pinned ? 'Pinned.' : 'Unpinned.');
    }

    public function destroy(DiscussionThread $thread)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin','teacher'])) abort(403);

        $thread->replies()->delete();
        $thread->delete();

        return back()->with('success', 'Discussion deleted.');
    }
}