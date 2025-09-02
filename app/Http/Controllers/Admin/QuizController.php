<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Quiz, Course, Section};
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with(['course:id,title','section:id,title'])
            ->withCount(['questions','attempts'])
            ->latest()->paginate(20);

        return view('admin.pages.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $courses  = Course::select('id','title')->orderBy('title')->get();
        $sections = Section::select('id','course_id','title')->orderBy('id')->get();
        return view('admin.pages.quizzes.form', [
            'quiz' => new Quiz(),
            'courses' => $courses,
            'sections' => $sections,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id'         => ['required','exists:courses,id'],
            'section_id'        => ['nullable','exists:sections,id'],
            'title'             => ['required','string','max:255'],
            'description'       => ['nullable','string'],
            'duration_minutes'  => ['nullable','integer','min:1'],
            'max_attempts'      => ['required','integer','min:1'],
            'pass_percentage'   => ['required','numeric','min:0','max:100'],
            'shuffle_questions' => ['boolean'],
            'shuffle_options'   => ['boolean'],
            'is_published'      => ['boolean'],
        ]);

        Quiz::create($data);
        return redirect()->route('admin.quizzes.index')->with('success','Quiz created.');
    }

    public function edit(Quiz $quiz)
    {
        $courses  = Course::select('id','title')->orderBy('title')->get();
        $sections = Section::select('id','course_id','title')->orderBy('id')->get();
        return view('admin.pages.quizzes.form', compact('quiz','courses','sections'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'course_id'         => ['required','exists:courses,id'],
            'section_id'        => ['nullable','exists:sections,id'],
            'title'             => ['required','string','max:255'],
            'description'       => ['nullable','string'],
            'duration_minutes'  => ['nullable','integer','min:1'],
            'max_attempts'      => ['required','integer','min:1'],
            'pass_percentage'   => ['required','numeric','min:0','max:100'],
            'shuffle_questions' => ['boolean'],
            'shuffle_options'   => ['boolean'],
            'is_published'      => ['boolean'],
        ]);
        $quiz->update($data);
        return redirect()->route('admin.quizzes.index')->with('success','Quiz updated.');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return back()->with('success','Quiz deleted.');
    }

    public function togglePublish(Quiz $quiz)
    {
        $quiz->is_published = ! $quiz->is_published;
        $quiz->save();
        return back()->with('success', 'Publish status updated.');
    }
}