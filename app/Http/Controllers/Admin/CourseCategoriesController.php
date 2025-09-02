<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CourseCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CourseCategoriesController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $categories = CourseCategory::when($q, function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('slug', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.pages.courseCategory.all_categories', compact('categories', 'q'));
    }

    public function create()
    {
        $category = new CourseCategory();
        return view('admin.pages.courseCategory.add_category', compact('category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:course_categories,name'],
            'slug'        => ['nullable', 'string', 'max:255', 'unique:course_categories,slug'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'image'], // 2MB max
        ]);

        $slug = $request->slug ?: $this->uniqueSlug($request->name);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('course_categories', 'public');
        }

        $category = CourseCategory::create([
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'image'       => $imagePath,
        ]);

        return redirect()
            ->route('admin.course-categories.index')
            ->with('success', "Category \"{$category->name}\" created.");
    }

    public function edit(CourseCategory $courseCategory)
    {
        $category = $courseCategory;
        return view('admin.pages.courseCategory.edit_category', compact('category'));
    }

    public function update(Request $request, CourseCategory $courseCategory)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:course_categories,name,' . $courseCategory->id],
            'slug'        => ['nullable', 'string', 'max:255', 'unique:course_categories,slug,' . $courseCategory->id],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'image'],
        ]);

        $slug = $request->slug ?: $this->uniqueSlug($request->name, $courseCategory->id);

        $imagePath = $courseCategory->image;

        if ($request->hasFile('image')) {
            // delete old image if exists
            if ($courseCategory->image && Storage::disk('public')->exists($courseCategory->image)) {
                Storage::disk('public')->delete($courseCategory->image);
            }
            $imagePath = $request->file('image')->store('course_categories', 'public');
        }

        $courseCategory->update([
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'image'       => $imagePath,
        ]);

        return redirect()
            ->route('admin.course-categories.index')
            ->with('success', "Category updated.");
    }

    public function destroy(CourseCategory $courseCategory)
    {
        $name = $courseCategory->name;

        // delete image if exists
        if ($courseCategory->image && Storage::disk('public')->exists($courseCategory->image)) {
            Storage::disk('public')->delete($courseCategory->image);
        }

        $courseCategory->delete();

        return redirect()
            ->route('admin.course-categories.index')
            ->with('success', "Category \"{$name}\" deleted.");
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;

        while (
            CourseCategory::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }
}