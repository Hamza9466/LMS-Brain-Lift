<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessLessonUpload;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::with(['section.course'])->latest()->get();
        return view('admin.pages.lessons.all_lessons', compact('lessons'));
    }

    public function create(Request $request)
    {
        $course = null;

        if ($request->filled('course_id')) {
            $course   = Course::findOrFail($request->course_id);
            $sections = Section::where('course_id', $course->id)->orderBy('title')->get();
        } else {
            $sections = Section::orderBy('title')->get();
        }

        return view('admin.pages.lessons.add_lesson', compact('sections', 'course'));
    }

    public function store(Request $request)
{
    // ✅ Normalize empty string to null
    if (($request->video_path ?? '') === '') {
        $request->merge(['video_path' => null]);
    }

    $request->validate([
        'section_id'  => ['required', Rule::exists('sections', 'id')],
        'title'       => ['required', 'string'],
        'description' => ['nullable', 'string'],
        'type'        => ['required', Rule::in(['video', 'pdf'])],

        // Video link
        'video_path'  => [
            'nullable','url',
            Rule::requiredIf(fn () => $request->type === 'video' && $request->video_mode === 'link'),
        ],

        // Video file
        'video_file'  => [
            'nullable','file',
            'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska',
            Rule::requiredIf(fn () => $request->type === 'video' && $request->video_mode === 'file'),
        ],

        // PDF
        'media'       => [
            'nullable','file','mimes:pdf',
            Rule::requiredIf(fn () => $request->type === 'pdf'),
        ],
    ], [
        'video_path.required' => 'Provide a video link.',
        'video_file.required' => 'Upload a video file.',
        'media.required'      => 'Upload a PDF file.',
    ]);

    $section = Section::select('id', 'course_id')->findOrFail($request->section_id);

    $data = $request->only('section_id', 'title', 'description', 'type');
    $data['course_id']  = $section->course_id;
    $data['video_path'] = null;
    $data['video_file'] = null;
    $data['pdf_path']   = null;

    // Handle VIDEO
    if ($request->type === 'video') {
        if ($request->video_mode === 'link') {
            // Save link
            $data['video_path'] = $request->video_path;
        } else {
            // Save uploaded file
            $data['video_file'] = $request->file('video_file')->store('lessons/videos', 'public');
        }
    }

    // Handle PDF
    if ($request->type === 'pdf' && $request->hasFile('media')) {
        $data['pdf_path'] = $request->file('media')->store('lessons', 'public');
    }

    Lesson::create($data);

    return redirect()->route('lessons.index')->with('success', 'Lesson created successfully.');
}


    public function edit(Lesson $lesson)
    {
        $sections = Section::where('course_id', $lesson->course_id)->orderBy('title')->get();
        return view('admin.pages.lessons.edit_lesson', compact('lesson', 'sections'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        // ✅ Normalize empty string to null
        if (($request->video_path ?? '') === '') {
            $request->merge(['video_path' => null]);
        }

        $request->validate([
            'section_id'  => ['required', Rule::exists('sections', 'id')],
            'title'       => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'type'        => ['required', Rule::in(['video', 'pdf'])],

            'video_path'  => ['sometimes','nullable','url','required_if:type,video','required_without:video_file'],
           'video_file'  => [
    'sometimes','nullable','file',
    'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska',
    'required_if:type,video','required_without:video_path'
],

            'media'       => ['sometimes','nullable','file','mimes:pdf','required_if:type,pdf'],
        ], [
            'video_path.required_without' => 'Provide a video link or upload a video file.',
            'video_file.required_without' => 'Upload a video file or provide a video link.',
        ]);

        $section = Section::select('id', 'course_id')->findOrFail($request->section_id);

        $data = $request->only('section_id', 'title', 'description', 'type');
        $data['course_id'] = $section->course_id;

        if ($request->type === 'video') {
            $data['pdf_path'] = null;

            if ($request->filled('video_path')) {
                if ($lesson->video_file && Storage::disk('public')->exists($lesson->video_file)) {
                    Storage::disk('public')->delete($lesson->video_file);
                }
                $data['video_path'] = $request->video_path;
                $data['video_file'] = null;

                $lesson->update($data);
                return redirect()->route('lessons.index')->with('success', 'Lesson updated successfully.');
            }

            if ($request->hasFile('video_file')) {
                if (config('queue.default') === 'sync') {
                    if ($lesson->video_file && Storage::disk('public')->exists($lesson->video_file)) {
                        Storage::disk('public')->delete($lesson->video_file);
                    }
                    $data['video_path'] = null;
                    $data['video_file'] = $request->file('video_file')->store('lessons/videos', 'public');
                    $data['pdf_path']   = null;

                    $lesson->update($data);
                    return redirect()->route('lessons.index')->with('success', 'Lesson updated successfully (sync upload).');
                }

                $ext     = strtolower($request->file('video_file')->getClientOriginalExtension());
                $unique  = uniqid('vid_', true) . '.' . $ext;
                $tempRel = $request->file('video_file')->storeAs('tmp/lessons', $unique, 'local');

                $data['video_path'] = null;
                $lesson->update($data);

                ProcessLessonUpload::dispatch($lesson->id, $tempRel, 'video');

                return redirect()->route('lessons.index')
                    ->with('success', 'Lesson updated. New video is processing in the background.');
            }

            $lesson->update($data);
            return redirect()->route('lessons.index')->with('success', 'Lesson updated successfully.');
        }

        // TYPE = PDF
        $data['video_path'] = null;
        $data['video_file'] = null;

        if ($request->hasFile('media')) {
            if ($lesson->pdf_path && Storage::disk('public')->exists($lesson->pdf_path)) {
                Storage::disk('public')->delete($lesson->pdf_path);
            }
            $data['pdf_path'] = $request->file('media')->store('lessons', 'public');
        }

        $lesson->update($data);

        return redirect()->route('lessons.index')->with('success', 'Lesson updated successfully.');
    }

    public function destroy(Lesson $lesson)
    {
        if ($lesson->video_file && Storage::disk('public')->exists($lesson->video_file)) {
            Storage::disk('public')->delete($lesson->video_file);
        }
        if ($lesson->pdf_path && Storage::disk('public')->exists($lesson->pdf_path)) {
            Storage::disk('public')->delete($lesson->pdf_path);
        }

        $lesson->delete();
        return back()->with('success', 'Lesson deleted successfully.');
    }
}