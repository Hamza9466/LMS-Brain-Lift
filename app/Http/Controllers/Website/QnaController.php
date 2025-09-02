<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\QnaThread;
use App\Models\QnaReply;
use App\Models\UserNotification; // <-- add
use App\Models\User;             // <-- (optional) if you notify admins
use Illuminate\Http\Request;

class QnaController extends Controller
{
    /** List Q&A threads for a lesson (student view) */
    public function index($lessonId)
    {
        $user   = auth()->user();
        $lesson = Lesson::with('section.course')->findOrFail($lessonId);
        $course = $lesson->section->course;

        if ($this->canModerateLesson($user->role, $course->teacher_id, $user->id)) {
            $threads = QnaThread::withCount('replies')
                ->where('lesson_id', $lesson->id)
                ->latest()
                ->paginate(15);
        } else {
            $threads = QnaThread::withCount('replies')
                ->where('lesson_id', $lesson->id)
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(15);
        }

        return view('admin.pages.Q&A.qna-index', compact('lesson','course','threads'));
    }

    /** Store a new thread (student creates) */
    public function store(Request $request, $lessonId)
    {
        $request->validate([
            'title' => ['required','string','max:255'],
            'body'  => ['required','string','max:5000'],
        ]);

        $lesson = Lesson::with('section.course')->findOrFail($lessonId);
        $course = $lesson->section->course;

        $thread = QnaThread::create([
            'lesson_id' => $lesson->id,
            'user_id'   => auth()->id(),
            'title'     => $request->title,
            'body'      => $request->body,
        ]);

        /* ---------------- NOTIFICATIONS ----------------
         * When a student opens a new thread:
         *   - notify the course teacher (if set)
         *   - (optional) notify all admins
         */
        $link = route('student.qna.show', $thread->id);

        // Notify course teacher
        if (!empty($course->teacher_id) && $course->teacher_id !== auth()->id()) {
            UserNotification::create([
                'user_id'    => $course->teacher_id,
                'title'      => 'New question in your course',
                'body'       => '“'.$thread->title.'”',
                'link_url'   => $link,
                'created_by' => auth()->id(),
            ]);
        }

        // (Optional) also notify all admins (except the creator)
        /*
        $adminIds = User::where('role','admin')->where('id','!=',auth()->id())->pluck('id');
        foreach ($adminIds as $aid) {
            UserNotification::create([
                'user_id'    => $aid,
                'title'      => 'New student question',
                'body'       => '“'.$thread->title.'”',
                'link_url'   => $link,
                'created_by' => auth()->id(),
            ]);
        }
        */

        return back()->with('success', 'Question posted. Only you and the instructor can view this thread.');
    }

    /** Show a single thread (privacy enforced) */
    public function show($threadId)
    {
        $user   = auth()->user();

        $thread = QnaThread::with([
                'lesson.section.course',
                'user',
                'replies.user'
            ])->findOrFail($threadId);

        $course = $thread->lesson->section->course;

        $isOwner = $thread->user_id === $user->id;
        $canMod  = $this->canModerateLesson($user->role, $course->teacher_id, $user->id);

        if (!($isOwner || $canMod)) {
            abort(403, 'You are not allowed to view this Q&A.');
        }

        return view('admin.pages.Q&A.qna-show', compact('thread'));
    }

    /** Reply to a thread (owner, teacher of the course, or admin) */
    public function reply(Request $request, $threadId)
    {
        $request->validate([
            'body' => ['required','string','max:5000'],
        ]);

        $user   = auth()->user();
        $thread = QnaThread::with('lesson.section.course')->findOrFail($threadId);
        $course = $thread->lesson->section->course;

        $isOwner = $thread->user_id === $user->id;
        $canMod  = $this->canModerateLesson($user->role, $course->teacher_id, $user->id);

        if (!($isOwner || $canMod)) {
            abort(403, 'You are not allowed to reply to this Q&A.');
        }

        QnaReply::create([
            'thread_id' => $thread->id,
            'user_id'   => $user->id,
            'body'      => $request->body,
        ]);

        /* ---------------- NOTIFICATIONS ----------------
         * Notify the *other* side when someone replies:
         *
         * - If teacher/admin replies -> notify the thread owner (student)
         * - If student replies       -> notify the course teacher (and optionally admins)
         */
        $link = route('student.qna.show', $thread->id);

        if ($user->role === 'teacher' || $user->role === 'admin') {
            // Notify student who owns the thread (avoid self-notify)
            if ($thread->user_id !== $user->id) {
                UserNotification::create([
                    'user_id'    => $thread->user_id,
                    'title'      => 'New reply to your question',
                    'body'       => '“'.$thread->title.'”',
                    'link_url'   => $link,
                    'created_by' => $user->id,
                ]);
            }
        } else {
            // Student replied -> notify course teacher
            if (!empty($course->teacher_id) && $course->teacher_id !== $user->id) {
                UserNotification::create([
                    'user_id'    => $course->teacher_id,
                    'title'      => 'Student replied in Q&A',
                    'body'       => '“'.$thread->title.'”',
                    'link_url'   => $link,
                    'created_by' => $user->id,
                ]);
            }

            // (Optional) also notify admins
            /*
            $adminIds = User::where('role','admin')->where('id','!=',$user->id)->pluck('id');
            foreach ($adminIds as $aid) {
                UserNotification::create([
                    'user_id'    => $aid,
                    'title'      => 'Student replied in Q&A',
                    'body'       => '“'.$thread->title.'”',
                    'link_url'   => $link,
                    'created_by' => $user->id,
                ]);
            }
            */
        }

        return back()->with('success', 'Reply posted.');
    }

    /** Helper: who can moderate a lesson’s Q&A? */
    private function canModerateLesson(string $role, ?int $courseTeacherId, int $userId): bool
    {
        if ($role === 'admin') return true;
        if ($role === 'teacher' && $courseTeacherId && $courseTeacherId === $userId) return true;
        return false;
    }
}