<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Course;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;
        $currencySymbol = '$';

        // Total courses in the system
        $totalCourses = Course::count();

        // Total students
        $totalStudents = User::where('role', 'student')->count();

        // Total enrolled courses across all students
        $enrolledCoursesCount = DB::table('course_user')
            ->whereNotNull('purchased_at')
            ->count();

        // Completed courses: all lessons completed by students
        $completedCoursesCount = DB::table('sections as s')
            ->join('lessons as l', 's.id', '=', 'l.section_id')
            ->leftJoin('lesson_user as lu', 'lu.lesson_id', '=', 'l.id')
            ->whereNotNull('lu.user_id') // any student who completed lesson
            ->select('s.course_id', DB::raw('COUNT(l.id) as total_lessons'), DB::raw('COUNT(lu.lesson_id) as completed_lessons'))
            ->groupBy('s.course_id')
            ->havingRaw('COUNT(l.id) = COUNT(lu.lesson_id)')
            ->get()
            ->count();

        $activeCoursesCount = max($enrolledCoursesCount - $completedCoursesCount, 0);

        // Earnings from paid orders
        $earnings = Order::where('status', 'paid')->sum('total');

        return view('admin.dashboard', compact(
            'enrolledCoursesCount',
            'completedCoursesCount',
            'activeCoursesCount',
            'totalCourses',
            'totalStudents',
            'earnings',
            'role',
            'currencySymbol'
        ));
    }
}