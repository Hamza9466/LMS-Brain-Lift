<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\PersonalDiscount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class PersonalDiscountController extends Controller
{
    /** Show the form + existing discounts for this course */
    public function index(Course $course)
    {
        // ✅ Do NOT reference 'name' column (it doesn't exist in your users table)
        // We also build a display_name in SQL for nicer ordering.
        $students = User::selectRaw("
                id,
                first_name,
                last_name,
                email,
                TRIM(CONCAT(COALESCE(first_name,''), ' ', COALESCE(last_name,''))) AS full_name
            ")
            ->orderByRaw("
                CASE
                    WHEN TRIM(CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,''))) <> '' THEN 0
                    WHEN email IS NOT NULL AND email <> '' THEN 1
                    ELSE 2
                END
            ")
            ->orderBy('full_name')
            ->orderBy('email')
            ->get();

        $discounts = PersonalDiscount::with('user')
            ->where('course_id', $course->id)
            ->orderByDesc('id')
            ->get();
        
        // ⬇️ Make sure this view path matches where your Blade file is saved
        return view('admin.pages.courses.discounts', compact('course','students','discounts'));
    }

    /** Create/Update a discount for user+course */
    public function store(Request $req, Course $course)
    {
        $data = $req->validate([
            'user_id'   => ['required','integer','exists:users,id'],
            'type'      => ['required', Rule::in(['percent','amount'])],
            'value'     => ['required','numeric','min:0.01'],
            'starts_at' => ['nullable','date'],
            'ends_at'   => ['nullable','date'],
            'max_uses'  => ['nullable','integer','min:1'],
            'active'    => ['nullable','boolean'],
        ]);

        $starts = $req->filled('starts_at') ? Carbon::parse($req->input('starts_at'), config('app.timezone')) : null;
        $ends   = $req->filled('ends_at')   ? Carbon::parse($req->input('ends_at'),   config('app.timezone')) : null;

        if ($starts && $ends && $ends->lt($starts)) {
            return back()->withErrors(['ends_at' => 'Ends must be after Starts'])->withInput();
        }

        $value = (float) $data['value'];
        if ($data['type'] === 'percent') {
            $value = max(0, min(100, $value)); // clamp %
        }

        PersonalDiscount::updateOrCreate(
            ['user_id' => (int)$data['user_id'], 'course_id' => (int)$course->id],
            [
                'type'      => $data['type'],
                'value'     => $value,
                'active'    => (bool)($data['active'] ?? false),
                'max_uses'  => $data['max_uses'] ?? null,
                // do not reset 'uses' on update
                'starts_at' => $starts,
                'ends_at'   => $ends,
            ]
        );

        return back()->with('success', 'Personal discount saved.');
    }

    /** Delete a discount (route param {discount}) */
    public function destroy(PersonalDiscount $discount)
    {
        $discount->delete();
        return back()->with('success', 'Personal discount removed.');
    }
}