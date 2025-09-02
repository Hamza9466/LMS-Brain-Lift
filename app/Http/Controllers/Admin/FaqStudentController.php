<?php

namespace App\Http\Controllers\Admin;

use App\Models\FaqStudent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FaqStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faqs = FaqStudent::latest()->paginate(10);
        return view('admin.pages.FaqStudent.all_question', compact('faqs'));
    }

    public function create()
    {
        return view('admin.pages.FaqStudent.add_question');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => ['required','string','max:255'],
            'resource' => ['nullable','string'],
        ]);

        FaqStudent::create($data);
        return redirect()->route('admin.faq-students.index')->with('success', 'FAQ created.');
    }

    public function edit(FaqStudent $faq_student)
    {
        return view('admin.pages.FaqStudent.edit_question', ['faq' => $faq_student]);
    }

    public function update(Request $request, FaqStudent $faq_student)
    {
        $data = $request->validate([
            'question' => ['required','string','max:255'],
            'resource' => ['nullable','string'],
        ]);

        $faq_student->update($data);
        return redirect()->route('admin.faq-students.index')->with('success', 'FAQ updated.');
    }

    public function destroy(FaqStudent $faq_student)
    {
        $faq_student->delete();
        return back()->with('success', 'FAQ deleted.');
    }

    // Optional show (not used in this UI)
    public function show(FaqStudent $faq_student)
    {
        return view('admin.pages.FaqStudent.show', compact('faq_student'));
    }
}