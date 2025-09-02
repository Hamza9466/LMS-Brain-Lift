<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class CourseViewController extends Controller
{
    public function show(Course $course)
    {
        $user = auth()->user();

        // Must be enrolled/purchased
        $hasAccess = $user->enrolledCourses()
            ->wherePivotNotNull('purchased_at')
            ->where('courses.id', $course->id)
            ->exists();
        abort_unless($hasAccess, 403);

        $sections = $course->sections()->with('lessons')->orderBy('id')->get();

        $completedIds = DB::table('lesson_user')
            ->where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->pluck('lesson_id')
            ->toArray();

        $sectionsData = $sections->map(function ($sec) use ($user, $completedIds) {
            $total = $sec->lessons->count();
            $done  = $sec->lessons->whereIn('id', $completedIds)->count();
            $progress = $total ? round(($done / max($total,1)) * 100) : 0;

            return [
                'section'     => $sec,
                'unlocked'    => $sec->isUnlockedFor($user),     // ← THE IMPORTANT LINE
                'isCompleted' => $total > 0 && $done === $total,
                'total'       => $total,
                'done'        => $done,
                'progress'    => $progress,
            ];
        });

        return view('admin.pages.enrolledCourses.view_course', compact('course', 'sectionsData'));
    }
}