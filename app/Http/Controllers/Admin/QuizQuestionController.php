<?php
// app/Http/Controllers/Admin/QuizQuestionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Quiz, QuizQuestion, QuizOption};
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class QuizQuestionController extends Controller
{
    public function index(Quiz $quiz)
    {
        $quiz->load(['questions.options' => fn ($q) => $q->orderBy('display_order')]);
        return view('admin.pages.quizzes.questions', compact('quiz'));
    }

  public function store(Request $request, Quiz $quiz)
{
    // 1) Normalize option rows: drop empty text rows
    $cleanOptions = collect($request->input('options', []))
        ->map(function ($op) {
            return [
                'text'    => isset($op['text']) ? trim($op['text']) : '',
                // keep the checkbox value; default to "0"
                'correct' => array_key_exists('correct', $op) ? (string)$op['correct'] : '0',
            ];
        })
        ->filter(fn ($op) => $op['text'] !== '') // <--- remove empty rows
        ->values()
        ->all();

    // re-merge cleaned options
    $request->merge(['options' => $cleanOptions]);

    // 2) Validate the cleaned payload
    $data = $request->validate([
        'type'                 => ['required', 'in:single,multiple,true_false'],
        'text'                 => ['required', 'string'],
        'points'               => ['required', 'numeric', 'min:0'],
        'options'              => ['required', 'array', 'min:2'],
        'options.*.text'       => ['required', 'string'],
        'options.*.correct'    => ['required', 'in:0,1'],
    ]);

    // 3) Enforce per-type rules (including T/F needs exactly 2 options, 1 correct)
    $this->enforceTypeRules($data['type'], $data['options']);

    // 4) Create question + options
    $q = $quiz->questions()->create([
        'type'          => $data['type'],
        'text'          => $data['text'],
        'points'        => $data['points'],
        'display_order' => (int)$quiz->questions()->max('display_order') + 1,
    ]);

    foreach ($data['options'] as $i => $op) {
        $q->options()->create([
            'text'          => $op['text'],
            'is_correct'    => ((int)($op['correct'] ?? 0) === 1),
            'display_order' => $i + 1,
        ]);
    }

    return back()->with('success', 'Question added.');
}

    public function edit(QuizQuestion $question)
    {
        $question->load(['quiz', 'options' => fn ($q) => $q->orderBy('display_order')]);
        return view('admin.pages.quizzes.question_edit', compact('question'));
    }

  public function update(Request $request, QuizQuestion $question)
{
    // 1) Normalize option rows: drop empty text rows
    $cleanOptions = collect($request->input('options', []))
        ->map(function ($op) {
            return [
                'text'    => isset($op['text']) ? trim($op['text']) : '',
                'correct' => array_key_exists('correct', $op) ? (string)$op['correct'] : '0',
            ];
        })
        ->filter(fn ($op) => $op['text'] !== '') // <--- remove empty rows
        ->values()
        ->all();

    $request->merge(['options' => $cleanOptions]);

    // 2) Validate
    $data = $request->validate([
        'type'                 => ['required', 'in:single,multiple,true_false'],
        'text'                 => ['required', 'string'],
        'points'               => ['required', 'numeric', 'min:0'],
        'options'              => ['required', 'array', 'min:2'],
        'options.*.text'       => ['required', 'string'],
        'options.*.correct'    => ['required', 'in:0,1'],
    ]);

    // 3) Enforce per-type rules
    $this->enforceTypeRules($data['type'], $data['options']);

    // 4) Update + resync options
    $question->update([
        'type'   => $data['type'],
        'text'   => $data['text'],
        'points' => $data['points'],
    ]);

    $question->options()->delete();
    foreach ($data['options'] as $i => $op) {
        $question->options()->create([
            'text'          => $op['text'],
            'is_correct'    => ((int)($op['correct'] ?? 0) === 1),
            'display_order' => $i + 1,
        ]);
    }

    return redirect()
        ->route('admin.quizzes.questions.index', $question->quiz_id)
        ->with('success', 'Question updated.');
}

    public function destroy(QuizQuestion $question)
    {
        $quizId = $question->quiz_id;
        $question->delete();
        return redirect()->route('admin.quizzes.questions.index', $quizId)->with('success', 'Question deleted.');
    }

    private function enforceTypeRules(string $type, array $options): void
    {
        $correctCount = collect($options)->filter(fn ($op) => ((int)($op['correct'] ?? 0) === 1))->count();

        if ($type === 'single' && $correctCount !== 1) {
            throw ValidationException::withMessages([
                'options' => 'Single choice must have exactly one correct option.',
            ]);
        }
        if ($type === 'multiple' && $correctCount < 1) {
            throw ValidationException::withMessages([
                'options' => 'Multiple choice must have at least one correct option.',
            ]);
        }
        if ($type === 'true_false') {
            if (count($options) !== 2) {
                throw ValidationException::withMessages([
                    'options' => 'True/False must have exactly 2 options.',
                ]);
            }
            if ($correctCount !== 1) {
                throw ValidationException::withMessages([
                    'options' => 'True/False must have exactly one correct option.',
                ]);
            }
        }
    }
}