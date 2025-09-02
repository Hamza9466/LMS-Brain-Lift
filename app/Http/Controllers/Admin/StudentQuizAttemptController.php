<?php

namespace App\Http\Controllers\Admin;

use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentQuizAttemptController extends Controller
{
      public function index()
    {
        $user = Auth::user();

        $attempts = QuizAttempt::with([
                'quiz',
                'answers.question.options',
            ])
            ->where('user_id', $user->id)
            ->orderByDesc('submitted_at')
            ->orderByDesc('started_at')
            ->paginate(10);

        return view('admin.pages.my-quiz-attempts', compact('attempts'));
    }

}