<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class EnrolledCourseController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Courses enrolled after purchase (uses course_user.purchased_at)
        $courses = $user->enrolledCourses()
            ->wherePivotNotNull('purchased_at')
            ->withCount('lessons') // provides lessons_count
            ->latest('course_user.purchased_at')
            ->get();

        // Completed lessons per course for this user
        $completedByCourse = DB::table('lesson_user')
            ->join('lessons', 'lesson_user.lesson_id', '=', 'lessons.id')
            ->join('sections', 'lessons.section_id', '=', 'sections.id')
            ->select('sections.course_id', DB::raw('COUNT(*) as completed_count'))
            ->where('lesson_user.user_id', $user->id)
            ->groupBy('sections.course_id')
            ->pluck('completed_count', 'sections.course_id');

        // Current user's reviews with needed fields, keyed by course_id
        $myReviewsByCourse = DB::table('course_reviews')
            ->where('user_id', $user->id)
            ->select('course_id','rating','title','comment')
            ->get()
            ->keyBy('course_id'); // array-like: course_id => {rating,title,comment}

        return view('admin.pages.enrolledCourses.enrolled_courses', compact(
            'courses', 'completedByCourse', 'myReviewsByCourse'
        ));
    }
}