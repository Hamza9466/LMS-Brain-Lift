<?php

namespace App\Http\Controllers\Website;

use App\Models\Course;
use App\Models\AboutBanner;
use App\Models\AboutIcon;
use App\Models\AboutPost;
use App\Models\AboutGalleryImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function about()
    {
       

        return view('website.pages.about');
    }
}