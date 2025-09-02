<?php

namespace App\Http\Controllers\Website;
use App\Models\CourseCategory;
use App\Models\Course;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
  public function home()
{
    $categories=CourseCategory::all();
    $courses = Course::all();
    // return view('website.home', compact('courses','navs'));
    return view('website.home',compact('categories','courses'));
}

}