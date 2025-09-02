<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;

class SectionViewController extends Controller
{
    public function show(Section $section)
    {
        $user   = auth()->user();
        $course = $section->course;

        $hasAccess = $user->enrolledCourses()
            ->wherePivotNotNull('purchased_at')
            ->where('courses.id', $course->id)
            ->exists();
        abort_unless($hasAccess, 403);

        if (!$section->isUnlockedFor($user)) {
            $sections = $course->sections()->with('lessons')->orderBy('id')->get();
            $idx  = $sections->search(fn ($s) => $s->id === $section->id);
            $prev = $idx > 0 ? $sections[$idx - 1] : null;

            if ($prev && ($target = $prev->firstTargetLessonFor($user))) {
                return redirect()
                    ->route('student.lessons.show', $target->id)
                    ->with('error', 'This section is locked. Complete all lessons AND pass the previous section’s MCQs.');
            }

            return redirect()->route('student.courses.show', $course->id)
                ->with('error', 'Section is locked.');
        }

        $target = $section->firstTargetLessonFor($user);
        if (!$target) {
            return redirect()->route('student.courses.show', $course->id)
                ->with('info', 'No lessons in this section yet.');
        }

        return redirect()->route('student.lessons.show', $target->id);
    }
}