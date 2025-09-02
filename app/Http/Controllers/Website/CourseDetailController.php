<?php
// app/Http/Controllers/Website/CourseDetailController.php
namespace App\Http\Controllers\Website;

use App\Models\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseDetailController extends Controller
{
    public function CourseDetail(Request $request, ?string $slug = null)
    {
        $slug = $slug ?? $request->query('slug') ?? $request->route('slug');
        abort_unless($slug, 404, 'Missing course slug');

        $course = Course::with([
            'category',
            'sections.lessons',   // keep only relations you actually have
        ])->where('slug', $slug)->firstOrFail();
        
 $approvedReviews = $course->approvedReviews;
        $averageRating   = round($approvedReviews->avg('rating'), 1);
        $totalRatings    = $approvedReviews->count();

        return view('website.pages.course-detail', compact('course','approvedReviews',
            'averageRating',
            'totalRatings'));
    }
}