<?php

namespace App\Http\Controllers\Website;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Http\Controllers\Controller;

class CourseGridController extends Controller
{
public function CourseGrid()
{
    $courses = Course::with(['category:id,name,slug'])
        ->orderByDesc('updated_at')
        ->get();  // no select() => all columns (including title)

    return view('website.pages.course', compact('courses'));
}
}