<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseCategoryController extends Controller
{
    public function CourseCategory(){
        return view('website.pages.course-category');
    }
}