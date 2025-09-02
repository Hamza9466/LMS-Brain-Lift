<?php

namespace App\Http\Controllers\Admin;

use App\Models\FaqTeacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FaqTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faqs = FaqTeacher::latest()->paginate(10);
        return view('admin.pages.FaqTeacher.all_question', compact('faqs'));
    }

    public function create()
    {
        return view('admin.pages.FaqTeacher.add_question');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => ['required','string','max:255'],
            'resource' => ['nullable','string'],
        ]);

        FaqTeacher::create($data);
        return redirect()->route('admin.faq-teachers.index')->with('success', 'FAQ created.');
    }

    public function edit(FaqTeacher $faq_teacher)
    {
        return view('admin.pages.FaqTeacher.edit_question', ['faq' => $faq_teacher]);
    }

    public function update(Request $request, FaqTeacher $faq_teacher)
    {
        $data = $request->validate([
            'question' => ['required','string','max:255'],
            'resource' => ['nullable','string'],
        ]);

        $faq_teacher->update($data);
        return redirect()->route('admin.faq-teachers.index')->with('success', 'FAQ updated.');
    }

    public function destroy(FaqTeacher $faq_teacher)
    {
        $faq_teacher->delete();
        return back()->with('success', 'FAQ deleted.');
    }

    // Optional
    public function show(FaqTeacher $faq_teacher)
    {
        return view('admin.pages.FaqTeacher.show', compact('faq_teacher'));
    }
}