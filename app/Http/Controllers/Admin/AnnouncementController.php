<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementUser;
use App\Models\User;
use App\Models\Course;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{
    // List + create form
    public function index()
    {
        $this->authorizeAdminOrTeacher();

        $announcements = Announcement::withCount('recipients')
            ->with('course')
            ->orderByDesc('created_at')
            ->paginate(20);

        $courses = Course::select('id','title')->orderBy('title')->get();

        return view('admin.pages.announcements.index', compact('announcements','courses'));
    }

    // Store + fan-out recipients
    public function store(Request $request)
    {
        $this->authorizeAdminOrTeacher();

        $data = $request->validate([
            'title'    => ['required','string','max:255'],
            'body'     => ['required','string'],
            'audience' => ['required','in:all,students,teachers,course_students'],
            'course_id'=> ['nullable','integer','exists:courses,id'],
            'is_published' => ['nullable','boolean'],
        ]);

        // If course_students, require course_id
        if ($data['audience'] === 'course_students' && empty($data['course_id'])) {
            return back()->withErrors('Please select a course for course_students audience.')->withInput();
        }

        $announcement = Announcement::create([
            'title'        => $data['title'],
            'body'         => $data['body'],
            'audience'     => $data['audience'],
            'course_id'    => $data['course_id'] ?? null,
            'is_published' => (bool)($data['is_published'] ?? true),
            'created_by'   => auth()->id(),
        ]);

        // Build recipients
        $users = collect();
        if ($data['audience'] === 'all') {
            $users = User::whereIn('role', ['student','teacher','admin'])->pluck('id');
        } elseif ($data['audience'] === 'students') {
            $users = User::where('role', 'student')->pluck('id');
        } elseif ($data['audience'] === 'teachers') {
            $users = User::where('role', 'teacher')->pluck('id');
        } elseif ($data['audience'] === 'course_students') {
            // Uses your existing course_user enrollment table
            $users = DB::table('course_user')
                ->where('course_id', $data['course_id'])
                ->when(DB::getSchemaBuilder()->hasColumn('course_user','purchased_at'), fn($q)=>$q->whereNotNull('purchased_at'))
                ->pluck('user_id');
        }

        $users = $users->unique()->values();

        // Fan out pivot + notification (bulk insert)
        $now = now();
        $pivotRows = [];
        $notifRows = [];

        foreach ($users as $uid) {
            $pivotRows[] = [
                'announcement_id' => $announcement->id,
                'user_id'         => $uid,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
            $notifRows[] = [
                'user_id'    => $uid,
                'title'      => 'New announcement: '.$announcement->title,
                'body'       => str($announcement->body)->limit(180),
                'link_url'   => route('student.announcements.index'),
                'created_by' => auth()->id(),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($pivotRows)) {
            AnnouncementUser::insert($pivotRows);
        }
        if (!empty($notifRows)) {
            UserNotification::insert($notifRows);
        }

        return back()->with('success', 'Announcement published to '.$users->count().' user(s).');
    }

    public function destroy(Announcement $announcement)
    {
        $this->authorizeAdminOrTeacher();

        $announcement->delete();
        return back()->with('success', 'Announcement deleted.');
    }

    public function toggle(Announcement $announcement)
    {
        $this->authorizeAdminOrTeacher();

        $announcement->is_published = !$announcement->is_published;
        $announcement->save();

        return back()->with('success', 'Publish state changed.');
    }

    private function authorizeAdminOrTeacher(): void
    {
        $u = auth()->user();
        if (!$u || !in_array($u->role, ['admin','teacher'])) {
            abort(403);
        }
    }
}