<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\{Lesson, Quiz};
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LessonViewController extends Controller
{
    /**
     * Lesson page: video/PDF, progress, and section quiz (when allowed).
     */
    public function show(Lesson $lesson)
    {
        $user   = auth()->user();
        $course = $lesson->section->course;

        // Must be purchased/enrolled
        $hasAccess = $user->enrolledCourses()
            ->wherePivotNotNull('purchased_at')
            ->where('courses.id', $course->id)
            ->exists();
        abort_unless($hasAccess, 403);

        // HARD GATE: previous section must be fully completed + quiz passed (if exists)
        if (!$lesson->section->isUnlockedFor($user)) {
            $sections = $course->sections()->with('lessons')->orderBy('id')->get();
            $idx  = $sections->search(fn ($s) => $s->id === $lesson->section_id);
            $prev = $idx > 0 ? $sections[$idx - 1] : null;

            if ($prev && ($targetPrev = $prev->firstTargetLessonFor($user))) {
                return redirect()
                    ->route('student.lessons.show', $targetPrev->id)
                    ->with('error', 'Locked until you complete all previous lessons and pass the previous section’s MCQs.');
            }

            return redirect()
                ->route('student.courses.show', $course->id)
                ->with('error', 'Section is locked.');
        }

        // For "Continue to next section" link
        $sections    = $course->sections()->with('lessons')->orderBy('id')->get();
        $idx         = $sections->search(fn ($s) => $s->id === $lesson->section_id);
        $nextSection = ($idx !== false && $idx < $sections->count() - 1) ? $sections[$idx + 1] : null;

        // Pivot for this lesson
        $pivot = DB::table('lesson_user')
            ->where('lesson_id', $lesson->id)
            ->where('user_id',   $user->id)
            ->first();

        $progressPercent = (float)($pivot->progress_percent ?? 0.0);
        $watchedOK       = $progressPercent >= 90.0;
        $quizPassedAt    = $pivot->quiz_passed_at ?? null;
        $isCompleted     = !empty($pivot?->completed_at);

        // Show quiz only after "Next" click for videos
        $showQuizFlag = request()->boolean('showquiz');
        $canSeeQuiz = ($lesson->type !== 'video') || ($watchedOK && $showQuizFlag);

        // Section quiz (published)
        $quizId = Quiz::where('course_id', $course->id)
            ->where('section_id', $lesson->section_id)
            ->where('is_published', true)
            ->value('id');

        // Lazy quiz data
        $quiz         = null;
        $attempt      = null;
        $lastAttempt  = null;
        $questions    = collect();
        $attemptsUsed = 0;
        $limit        = 0;
        $unlimited    = true;
        $canRetry     = false;

        if ($canSeeQuiz && $quizId) {
            $quiz = Quiz::find($quizId);
            if ($quiz) {
                $attemptsUsed = $quiz->attempts()->where('user_id', $user->id)->count();
                $limit        = (int)($quiz->max_attempts ?? 0);
                $unlimited    = ($limit === 0);

                $inProgress = $quiz->attempts()
                    ->where('user_id', $user->id)
                    ->where('status', 'in_progress')
                    ->latest()->first();

                $lastAttempt = $quiz->attempts()
                    ->where('user_id', $user->id)
                    ->where('status', 'submitted')
                    ->latest()->first();

                $attempt  = $inProgress;
                $canRetry = (!$lastAttempt || !$lastAttempt->is_passed) && ($unlimited || $attemptsUsed < $limit);

                if ($attempt && $attempt->status === 'in_progress') {
                    $questions = $quiz->questions()->with('options')->orderBy('display_order')->get();
                    if ($quiz->shuffle_questions) $questions = $questions->shuffle();
                }
            }
        }

        return view('admin.pages.enrolledCourses.lesson_show', compact(
            'course','lesson',
            'isCompleted',
            'quiz','attempt','lastAttempt','questions','nextSection',
            'attemptsUsed','limit','unlimited','canRetry',
            'progressPercent','watchedOK','quizPassedAt','canSeeQuiz',
            'quizId','showQuizFlag'
        ));
    }

    /**
     * Save progress (never decreases).
     * Also: if PDF with NO quiz and percent=100 => mark completed.
     */
    public function progress(Request $request, Lesson $lesson)
    {
        $user   = $request->user();
        $course = $lesson->section->course;

        $hasAccess = $user->enrolledCourses()
            ->wherePivotNotNull('purchased_at')
            ->where('courses.id', $course->id)
            ->exists();
        abort_unless($hasAccess, 403);

        $data = $request->validate([
            'percent' => 'required|numeric|min:0|max:100',
        ]);
        $percent = round((float)$data['percent'], 2);

        DB::table('lesson_user')->updateOrInsert(
            ['lesson_id' => $lesson->id, 'user_id' => $user->id],
            [
                'progress_percent' => DB::raw('GREATEST(COALESCE(progress_percent,0), '.$percent.')'),
                'watched_at'       => now(),
                'updated_at'       => now(),
                'created_at'       => now(),
            ]
        );

        // PDF + NO quiz + 100% => mark as completed
        $hasQuiz = Quiz::where('course_id', $course->id)
            ->where('section_id', $lesson->section_id)
            ->where('is_published', true)
            ->exists();

        if ($lesson->type === 'pdf' && !$hasQuiz && $percent >= 100) {
            DB::table('lesson_user')
                ->where('lesson_id', $lesson->id)
                ->where('user_id', $user->id)
                ->update([
                    'completed_at' => DB::raw('COALESCE(completed_at, NOW())'),
                    'updated_at'   => now(),
                ]);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Manual completion (allowed after passing the section quiz).
     */
    public function complete(Lesson $lesson)
    {
        $user = auth()->user();

        $pivot = DB::table('lesson_user')
            ->where('lesson_id', $lesson->id)
            ->where('user_id',   $user->id)
            ->first();

        abort_unless($pivot && !empty($pivot->quiz_passed_at), 403, 'Please pass the quiz first.');

        DB::table('lesson_user')->updateOrInsert(
            ['lesson_id' => $lesson->id, 'user_id' => $user->id],
            ['completed_at' => now(), 'updated_at' => now(), 'created_at' => now()]
        );

        return redirect()
            ->route('student.lessons.show', $lesson->id)
            ->with('success', 'Lesson completed!');
    }

    public function download(Lesson $lesson)
    {
        $user   = auth()->user();
        $course = $lesson->section->course;

        $hasAccess = $user->enrolledCourses()
            ->wherePivotNotNull('purchased_at')
            ->where('courses.id', $course->id)
            ->exists();
        abort_unless($hasAccess, 403);

        abort_unless($lesson->type === 'pdf' && $lesson->pdf_path, 404);

        $path = storage_path('app/public/'.$lesson->pdf_path);
        abort_unless(is_file($path), 404);

        $filename = Str::slug($lesson->title ?: 'lesson').'.pdf';
        return response()->download($path, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}