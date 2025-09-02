<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\DiscussionThread;
use App\Models\DiscussionReply;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscussionController extends Controller
{
    public function index(Lesson $lesson)
    {
        $course = $lesson->course;
        $threads = DiscussionThread::where('lesson_id', $lesson->id)
            ->orderByDesc('is_pinned')
            ->orderBy('created_at','desc')
            ->withCount('replies')
            ->paginate(15);

        return view('admin.pages.Q&A.discussion-index', compact('lesson','course','threads'));
    }

    public function store(Request $request, Lesson $lesson)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'nullable|string',
        ]);

        DiscussionThread::create([
            'course_id' => $lesson->course_id,
            'lesson_id' => $lesson->id,
            'user_id'   => Auth::id(),
            'title'     => $request->title,
            'body'      => $request->body,
        ]);

        return redirect()->route('student.discussion.index', $lesson->id)->with('success', 'Thread created.');
    }

    public function show(DiscussionThread $thread)
    {
        $lesson = $thread->lesson;
        $course = $thread->course;
        $replies = $thread->replies()->with('user')->get();

        return view('admin.pages.Q&A.discussion-show', compact('thread','lesson','course','replies'));
    }

    public function reply(Request $request, DiscussionThread $thread)
    {
        $request->validate(['body' => 'required|string']);

        DiscussionReply::create([
            'thread_id' => $thread->id,
            'user_id'   => Auth::id(),
            'body'      => $request->body,
        ]);

        return back()->with('success', 'Reply posted.');
    }
}