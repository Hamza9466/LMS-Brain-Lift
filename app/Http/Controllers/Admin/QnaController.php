<?php

namespace App\Http\Controllers\Admin;

use App\Models\QnaReply;
use App\Models\QnaThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class QnaController extends Controller
{
    // Admin/Teacher listing (no lesson required)
public function index(Request $request)
{
    $user   = Auth::user();
    $filter = $request->query('filter'); // 'replied' | 'unreplied' | null

    // When admin/teacher opens the list, mark “seen” for the red counter
    Cache::put("qna_seen_at_{$user->id}", now(), 60 * 60 * 24 * 30);

    // Base scope
    $base = QnaThread::query()
        ->with(['lesson.section.course', 'user'])
        ->withCount('replies');

    if ($user->role === 'teacher') {
        $base->whereHas('lesson.section.course', function ($qq) use ($user) {
            $qq->where('teacher_id', $user->id);
        });
    }

    // Build counts for the filter pills (respecting the same scope)
    $counts = [
        'all'       => (clone $base)->count(),
        'replied'   => (clone $base)->has('replies')->count(),         // any reply
        'unreplied' => (clone $base)->doesntHave('replies')->count(),  // zero replies
    ];

    // Apply current filter
    $q = clone $base;
    if ($filter === 'replied') {
        $q->has('replies');
        // If you want “replied by staff only”, swap to:
        // $q->whereHas('replies.user', fn($uq) => $uq->whereIn('role',['admin','teacher']));
    } elseif ($filter === 'unreplied') {
        $q->doesntHave('replies');
    }

    $threads = $q->orderByDesc('is_pinned')
        ->orderBy('created_at','desc')
        ->paginate(20)
        ->withQueryString();

    return view('admin.pages.Q&A.qna-admin-index', compact('threads','counts'));
}



    // Toggle official answer on a reply (Admin/Teacher)
    public function markAnswer(QnaReply $reply)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin','teacher'])) {
            abort(403);
        }

        $reply->is_answer = !$reply->is_answer;
        $reply->save();

        return back()->with('success', $reply->is_answer ? 'Marked as answer.' : 'Answer unmarked.');
    }

    // Open/Close a thread (Admin/Teacher)
    public function toggleStatus(QnaThread $thread)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin','teacher'])) {
            abort(403);
        }

        $thread->status = $thread->status === 'open' ? 'closed' : 'open';
        $thread->save();

        return back()->with('success', 'Thread status updated.');
    }

    // Delete thread (Admin/Teacher)
    public function destroy(QnaThread $thread)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin','teacher'])) {
            abort(403);
        }

        $thread->replies()->delete();
        $thread->delete();

        return back()->with('success', 'Thread deleted.');
    }
}