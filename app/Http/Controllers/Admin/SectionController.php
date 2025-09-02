<?php
namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::with('course')->latest()->get();
            $lessons = Lesson::with('section')->latest()->get(); 

        return view('admin.pages.sections.all_sections', compact('sections','lessons'));
    }

   public function create(Request $request)
{
    
    if ($request->has('course_id')) {
        $course = Course::findOrFail($request->course_id);
        return view('admin.pages.sections.add_section', compact('course'));
    }

    $courses = Course::all();
    return view('admin.pages.sections.add_section', compact('courses'));
}
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string',
        ]);

        Section::create($request->all());

        return redirect()->route('sections.index')->with('success', 'Section created');
    }

    public function show(Section $section)
    {
        return view('admin.pages.sections.edit_section', compact('section'));
    }

    public function edit(Section $section)
    {
        $courses = Course::all();
        return view('admin.pages.sections.edit_section', compact('section', 'courses'));
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string',
        ]);

        $section->update($request->all());

        return redirect()->route('sections.index')->with('success', 'Section updated');
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return back()->with('success', 'Section deleted');
    }
}