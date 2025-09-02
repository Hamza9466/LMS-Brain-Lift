<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Quiz, QuizAttempt, QuizAnswer};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizPlayController extends Controller
{
    public function start(Request $request, Quiz $quiz)
    {
        $user = $request->user();

        // Must own the course
        $hasAccess = DB::table('course_user')
            ->where('course_id', $quiz->course_id)
            ->where('user_id', $user->id)
            ->whereNotNull('purchased_at')
            ->exists();
        abort_unless($hasAccess, 403);

        // Attempts left? 0 = unlimited
        $count = $quiz->attempts()->where('user_id', $user->id)->count();
        if ((int)$quiz->max_attempts > 0) {
            abort_if($count >= (int)$quiz->max_attempts, 403, 'No attempts left.');
        }

        // Lesson we came from (enforce 90% watch for videos)
        $lessonId = (int)$request->input('lesson_id');
        if ($lessonId) {
            $lesson = \App\Models\Lesson::find($lessonId);
            if ($lesson && $lesson->type === 'video') {
                $pivot = DB::table('lesson_user')
                    ->where('lesson_id', $lesson->id)
                    ->where('user_id',   $user->id)
                    ->first();
                $progressPercent = (float)($pivot->progress_percent ?? 0);
                abort_unless($progressPercent >= 90.0, 403, 'Please watch at least 90% of the video first.');
            }
            $request->session()->put("quiz_lesson_{$quiz->id}_{$user->id}", $lessonId);
        }

        $attempt = $quiz->attempts()->create([
            'user_id'    => $user->id,
            'started_at' => now(),
            'status'     => 'in_progress',
            'ip_address' => $request->ip(),
        ]);

        if ($request->filled('redirect')) {
            return redirect($request->input('redirect'))->with('success', 'MCQs opened.');
        }

        return redirect()->route('student.attempts.take', $attempt->id);
    }

    public function take(Request $request, QuizAttempt $attempt)
    {
        abort_unless($attempt->user_id === $request->user()->id, 403);
        abort_if($attempt->status !== 'in_progress', 403, 'Attempt already submitted.');

        $quiz = $attempt->quiz()->with(['questions.options'])->first();
        $questions = $quiz->shuffle_questions ? $quiz->questions->shuffle() : $quiz->questions;

        return view('student.quizzes.take', compact('quiz', 'attempt', 'questions'));
    }

    public function submit(Request $request, QuizAttempt $attempt)
    {
        abort_unless($attempt->user_id === $request->user()->id, 403);
        abort_if($attempt->status !== 'in_progress', 403);

        $quiz = $attempt->quiz()->with(['questions.options'])->firstOrFail();

        $data = $request->validate([
            'answers'   => 'array',
            'answers.*' => 'array',
        ]);
        $answers = $data['answers'] ?? [];

        $score = 0.0;

        DB::transaction(function () use ($answers, $quiz, $attempt, &$score) {
            foreach ($quiz->questions as $q) {
                $selected = array_map('intval', $answers[$q->id] ?? []);
                $correct  = $q->correctOptionIds();

                $isCorrect = !array_diff($selected, $correct) && !array_diff($correct, $selected);
                $awarded   = $isCorrect ? (float) $q->points : 0.0;
                $score    += $awarded;

                QuizAnswer::updateOrCreate(
                    ['attempt_id' => $attempt->id, 'question_id' => $q->id],
                    ['selected_option_ids' => $selected, 'points_awarded' => $awarded]
                );
            }

            $attempt->update([
                'submitted_at'     => now(),
                'status'           => 'submitted',
                'duration_seconds' => now()->diffInSeconds($attempt->started_at ?? now()),
                'score'            => $score,
            ]);
        });

        // Robust pass logic
        $totalPoints  = (float)$quiz->questions->sum('points');
        $requiredPass = is_null($quiz->pass_percentage) ? 50.0 : (float)$quiz->pass_percentage;

        if ($totalPoints <= 0.0 || $quiz->questions->count() <= 0) {
            $percentage = 0.0;
            $passed     = false;
        } else {
            $percentage = round(($score / $totalPoints) * 100, 2);
            $passed     = $percentage >= $requiredPass;
        }

        $attempt->update([
            'percentage' => $percentage,
            'is_passed'  => $passed,
        ]);

        // Which lesson started this quiz?
        $key = "quiz_lesson_{$quiz->id}_{$request->user()->id}";
        $lessonIdFromSession = (int)($request->session()->pull($key) ?? 0);
        $lessonIdFromRequest = (int)$request->input('lesson_id', 0);
        $lessonId = $lessonIdFromSession ?: $lessonIdFromRequest;

        // Only when PASSED: set quiz_passed_at and completed_at
        if ($passed && $lessonId) {
            DB::table('lesson_user')->updateOrInsert(
                ['lesson_id' => $lessonId, 'user_id' => $request->user()->id],
                [
                    'quiz_passed_at' => now(),
                    'completed_at'   => now(),
                    'updated_at'     => now(),
                    'created_at'     => now(),
                ]
            );
        }

        return redirect()->route('student.attempts.result', $attempt->id);
    }

    public function result(Request $request, QuizAttempt $attempt)
    {
        abort_unless($attempt->user_id === $request->user()->id, 403);

        $attempt->load(['quiz','answers','answers.question.options']);
        return view('admin.pages.enrolledCourses.result', compact('attempt'));
    }
}