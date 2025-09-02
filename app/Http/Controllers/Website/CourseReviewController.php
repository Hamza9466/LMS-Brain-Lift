<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseReviewController extends Controller
{
    public function __construct()
    {
        // Users must be logged in to submit reviews
        $this->middleware('auth')->only(['store', 'my']);

        // Admin-only moderation endpoints
        $this->middleware(['auth','role:admin'])->only(['index','update','destroy']);
    }

    /**
     * USER: POST /courses/{course}/reviews
     * Store one review per user per course. Saved as pending (is_approved=false).
     */
    public function store(Request $request, Course $course)
    {
        $data = $request->validate([
            'rating'  => ['required','integer','min:1','max:5'],
            'title'   => ['nullable','string','max:255'],
            'comment' => ['nullable','string'],
        ]);

        // Prevent duplicates (one review per user per course)
        $exists = CourseReview::where('course_id', $course->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($exists) {
            return back()->with('info', 'You already submitted a review for this course.');
        }

        CourseReview::create([
            'course_id'   => $course->id,
            'user_id'     => Auth::id(),
            'rating'      => $data['rating'],
            'title'       => $data['title'] ?? null,
            'comment'     => $data['comment'] ?? null,
            'is_approved' => false, // wait for admin approval
        ]);

        return back()->with('success', 'Thanks! Your review is submitted and awaiting approval.');
    }

    /**
     * ADMIN: GET /admin/coursereview
     * List reviews with optional filter ?status=approved|pending
     */
    public function index(Request $request)
    {
        $status = $request->query('status'); // 'approved' | 'pending' | null

        $reviews = CourseReview::with([
                'course:id,title,slug',
                // Your users table doesn't have "name", so only select what exists:
                'user' => fn ($q) => $q->select('id', 'email'),
            ])
            ->when($status === 'approved', fn($q) => $q->where('is_approved', true))
            ->when($status === 'pending',  fn($q) => $q->where('is_approved', false))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.pages.reviews.index', compact('reviews','status'));
    }

    /**
     * STUDENT: GET /dashboard/reviews
     * The student's own review history page.
     */
    public function my(Request $request)
    {
        $reviews = CourseReview::with(['course:id,title,thumbnail'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('admin.pages.reviews.student_index', compact('reviews'));
    }

    /**
     * ADMIN: PUT /admin/coursereview/{coursereview}
     * Approve or Reject a review (toggle is_approved).
     */
    public function update(Request $request, CourseReview $coursereview)
    {
        $data = $request->validate([
            'is_approved' => ['required','in:0,1'],
        ]);

        $coursereview->is_approved = (bool) $data['is_approved'];
        $coursereview->save();

        // If you maintain aggregates on Course, update them:
        $coursereview->course?->recalculateRatings();

        return back()->with('success', 'Review status updated.');
    }

    /**
     * ADMIN: DELETE /admin/coursereview/{coursereview}
     * Delete a review.
     */
    public function destroy(CourseReview $coursereview)
    {
        $course = $coursereview->course;
        $coursereview->delete();
        $course?->recalculateRatings();

        return back()->with('success', 'Review deleted.');
    }
}