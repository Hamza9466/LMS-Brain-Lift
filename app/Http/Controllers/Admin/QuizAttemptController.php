<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Quiz, QuizAttempt};
use App\Http\Controllers\Controller;

class QuizAttemptController extends Controller
{
    // List attempts for a quiz (admin)
    public function index(Request $request, Quiz $quiz)
    {
        $attempts = QuizAttempt::with(['user:id,email'])
            ->where('quiz_id', $quiz->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.pages.quizzes.attempts_index', compact('quiz', 'attempts'));
    }

    // Delete all attempts for a given user on this quiz
    public function resetUser(Request $request, Quiz $quiz, User $user)
    {
        // Optional: ensure this user actually has attempts for this quiz
        $exists = QuizAttempt::where('quiz_id', $quiz->id)->where('user_id', $user->id)->exists();
        if (!$exists) {
            return back()->with('info', 'No attempts found for this student on this quiz.');
        }

        DB::transaction(function () use ($quiz, $user) {
            // If your FK is set to cascade (recommended), deleting attempts is enough.
            // Otherwise, you could delete answers explicitly first.
            QuizAttempt::where('quiz_id', $quiz->id)
                ->where('user_id', $user->id)
                ->delete();
        });

        return back()->with('success', 'All attempts for this student have been cleared.');
    }

    // Attempt detail (admin)
    public function show(QuizAttempt $attempt)
    {
        $attempt->load(['quiz','user:id,email','answers.question.options']);
        return view('admin.pages.quizzes.attempts_show', compact('attempt'));
    }

    // Delete attempt (optional)
    public function destroy(QuizAttempt $attempt)
    {
        $attempt->delete();
        return back()->with('success', 'Attempt deleted.');
    }
}