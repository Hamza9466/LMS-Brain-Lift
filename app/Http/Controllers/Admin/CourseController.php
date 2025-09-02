<?php
// app/Http/Controllers/Admin/CourseController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index()
    {
        // No teacher eager-load
        $courses = Course::with('category')
            ->latest()
            ->paginate(10);

        return view('admin.pages.courses.all_courses', compact('courses'));
    }

    public function create()
    {
        $categories = \App\Models\CourseCategory::orderBy('name')->get();
        return view('admin.pages.courses.add_course', compact('categories'));
    }

    /** STORE */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // identity & display
            'title'              => ['required','string','max:255'],
            'slug'               => ['nullable','string','max:255', Rule::unique('courses','slug')],
            'short_description'  => ['nullable','string','max:255'],
            'description'        => ['required','string'],
            'long_description'   => ['nullable','string'],
            'thumbnail'          => ['nullable','image'],
            'subject'            => ['nullable','string','max:255'],
            // (promo_video_url removed)

            // level/lang/stats
            'level'                  => ['required','string','max:100'],
            'language'               => ['nullable','string','max:100'],
            'total_lessons'          => ['nullable','integer','min:0'],
            'total_duration_minutes' => ['nullable','integer','min:0'],

            // category
            'category_id' => ['nullable', 'exists:course_categories,id'],

            // list inputs (as text; we’ll parse)
            'what_you_will_learn' => ['nullable','string'],
            'requirements'        => ['nullable','string'],
            'who_is_for'          => ['nullable','string'],
            'tags'                => ['nullable','string'],
            'materials'           => ['nullable','string'],

            // pricing
            'is_free'             => ['sometimes','boolean'],
            'price'               => ['nullable','numeric','min:0'],
            'compare_at_price'    => ['nullable','numeric','min:0'],
            'discount_percentage' => ['nullable','numeric','min:0','max:100'],
            'discount_price'      => ['nullable','numeric','min:0'],

            // publish/assign
            'status'              => ['required', Rule::in(['draft','published'])],
            'published_at'        => ['nullable','date'],
            'is_featured'         => ['sometimes','boolean'],
        ]);

        $data = $validated;

        // Owner: always current user (no teacher selection in UI)
        $data['teacher_id'] = Auth::id();

        // Slug (generate if blank) with uniqueness
        $base         = $request->filled('slug') ? $request->input('slug') : $request->input('title');
        $data['slug'] = $this->uniqueSlug($base);

        // Normalize list fields to arrays (store as JSON)
        $data['what_you_will_learn'] = $this->toLines($request->input('what_you_will_learn'));
        $data['requirements']        = $this->toLines($request->input('requirements'));
        $data['who_is_for']          = $this->toLines($request->input('who_is_for'));
        $data['tags']                = $this->toCsv($request->input('tags'));
        $data['materials']           = $this->toCsv($request->input('materials'));

        // Flags
        $data['is_free']     = $request->boolean('is_free');
        $data['is_featured'] = $request->boolean('is_featured');

        // Pricing precedence
        if ($data['is_free']) {
            $data['price']               = 0;
            $data['compare_at_price']    = null;
            $data['discount_price']      = null;
            $data['discount_percentage'] = null;
        } else {
            if (isset($data['discount_percentage'], $data['price'])
                && $data['discount_percentage'] !== null && $data['price'] !== null) {
                $data['discount_price'] = $data['discount_price']
                    ?? round($data['price'] - ($data['price'] * $data['discount_percentage'] / 100), 2);
            }
        }

        // Thumbnail
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $data['created_by']  = Auth::id();
        $data['category_id'] = $request->input('category_id') ?: null;

        Course::create($data);

        return redirect()->route('courses.index')->with('success', 'Course created successfully');
    }

    public function show($id)
    {
        $course = Course::with('category')->findOrFail($id);
        return view('admin.pages.courses.view_course', compact('course'));
    }

    public function edit($id)
    {
        $course     = Course::findOrFail($id);
        $categories = \App\Models\CourseCategory::orderBy('name')->get();

        return view('admin.pages.courses.edit_course', compact('course','categories'));
    }

    /** UPDATE */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            // identity & display
            'title'              => ['required','string','max:255'],
            'slug'               => ['nullable','string','max:255', Rule::unique('courses','slug')->ignore($course->id)],
            'short_description'  => ['nullable','string','max:255'],
            'description'        => ['required','string'],
            'long_description'   => ['nullable','string'],
            'thumbnail'          => ['nullable','image'],
            'subject'            => ['nullable','string','max:255'],
            // (promo_video_url removed)

            // level/lang/stats
            'level'                  => ['required','string','max:100'],
            'language'               => ['nullable','string','max:100'],
            'total_lessons'          => ['nullable','integer','min:0'],
            'total_duration_minutes' => ['nullable','integer','min:0'],

            // category
            'category_id' => ['nullable', 'exists:course_categories,id'],

            // list inputs
            'what_you_will_learn' => ['nullable','string'],
            'requirements'        => ['nullable','string'],
            'who_is_for'          => ['nullable','string'],
            'tags'                => ['nullable','string'],
            'materials'           => ['nullable','string'],

            // pricing
            'is_free'             => ['sometimes','boolean'],
            'price'               => ['nullable','numeric','min:0'],
            'compare_at_price'    => ['nullable','numeric','min:0'],
            'discount_percentage' => ['nullable','numeric','min:0','max:100'],
            'discount_price'      => ['nullable','numeric','min:0'],

            // publish/assign
            'status'              => ['required', Rule::in(['draft','published'])],
            'published_at'        => ['nullable','date'],
            'is_featured'         => ['sometimes','boolean'],
        ]);

        $data = $validated;

        // Preserve existing owner; no teacher input
        $data['teacher_id'] = $course->teacher_id;

        // Slug
        $base         = $request->filled('slug') ? $request->input('slug') : $request->input('title');
        $data['slug'] = $this->uniqueSlug($base, $course->id);

        // Lists
        $data['what_you_will_learn'] = $this->toLines($request->input('what_you_will_learn'));
        $data['requirements']        = $this->toLines($request->input('requirements'));
        $data['who_is_for']          = $this->toLines($request->input('who_is_for'));
        $data['tags']                = $this->toCsv($request->input('tags'));
        $data['materials']           = $this->toCsv($request->input('materials'));

        // Flags
        $data['is_free']     = $request->boolean('is_free');
        $data['is_featured'] = $request->boolean('is_featured');

        // Pricing precedence
        if ($data['is_free']) {
            $data['price']               = 0;
            $data['compare_at_price']    = null;
            $data['discount_price']      = null;
            $data['discount_percentage'] = null;
        } else {
            if (isset($data['discount_percentage'], $data['price'])
                && $data['discount_percentage'] !== null && $data['price'] !== null) {
                $data['discount_price'] = $data['discount_price']
                    ?? round($data['price'] - ($data['price'] * $data['discount_percentage'] / 100), 2);
            }
        }

        // Thumbnail
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $data['category_id'] = $request->input('category_id') ?: null;

        $course->update($data);

        return redirect()->route('courses.index')->with('success', 'Course updated successfully');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted');
    }

    /* ================= Helpers ================= */

    /** Convert multi-line textarea to array */
    private function toLines(?string $text): array
    {
        if (!$text) return [];
        $lines = preg_split('/\r\n|\r|\n/', $text);
        return array_values(array_filter(array_map('trim', $lines), fn($v) => $v !== ''));
    }

    /** Convert comma-separated to array */
    private function toCsv(?string $text): array
    {
        if (!$text) return [];
        $items = array_map('trim', explode(',', $text));
        return array_values(array_filter($items, fn($v) => $v !== ''));
    }

    /**
     * Ensure slug is unique. If exists, append -2, -3, ...
     */
    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug     = Str::slug($base) ?: Str::random(8);
        $original = $slug;
        $i = 2;

        $exists = function ($s) use ($ignoreId) {
            return Course::where('slug', $s)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists();
        };

        while ($exists($slug)) {
            $slug = $original . '-' . $i;
            $i++;
        }
        return $slug;
    }
}