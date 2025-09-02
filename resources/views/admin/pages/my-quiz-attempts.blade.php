@extends('admin.layouts.main')

@section('content')
@php
use Illuminate\Support\Facades\Auth;

$user = Auth::user();
$attempts = \App\Models\QuizAttempt::query()
    ->with(['quiz', 'answers.question.options'])
    ->where('user_id', optional($user)->id)
    ->orderByDesc('submitted_at')
    ->orderByDesc('started_at')
    ->paginate(10);

if (!function_exists('sameSet')) {
    function sameSet(array $a, array $b): bool {
        sort($a); sort($b);
        return $a === $b;
    }
}

if (!function_exists('attemptStats')) {
    function attemptStats(\App\Models\QuizAttempt $a): array {
        $answers = $a->answers ?? collect();
        $totalQuestions = $answers->count();
        $totalMarks = 0.0;
        $correct = 0;
        $incorrect = 0;
        $earned = 0.0;

        foreach ($answers as $ans) {
            $q = $ans->question;
            $qPoints = (float)($q->points ?? 1);
            $totalMarks += $qPoints;

            $correctIds = $q && $q->relationLoaded('options')
                ? $q->options->where('is_correct', 1)->pluck('id')->all()
                : [];

            $selected = (array)($ans->selected_option_ids ?? []);
            $isCorrect = sameSet($selected, $correctIds);

            if ($isCorrect) $correct++; else $incorrect++;
            $earned += !is_null($ans->points_awarded) ? (float)$ans->points_awarded : ($isCorrect ? $qPoints : 0.0);
        }

        $percent = $totalMarks > 0 ? round(($earned / $totalMarks) * 100, 2) : 0.0;
        return [$totalQuestions, $totalMarks, $correct, $incorrect, $earned, $percent];
    }
}
@endphp

<div class="container py-4">
    <h4 class="mb-4 fw-bold text-primary">My Quiz Attempts</h4>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead style="background: linear-gradient(90deg, #02409c, #12a0a0); color: #fff;">
                        <tr>
                            <th class="px-3 py-3">#</th>
                            <th class="py-3">Quiz Title</th>
                            <th class="py-3">Correct</th>
                            <th class="py-3">Incorrect</th>
                            <th class="py-3">Earned Marks</th>
                            <th class="py-3">Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attempts as $index => $a)
                            @php
                                [$totalQ, $totalMarks, $correct, $incorrect, $earned, $percent] = attemptStats($a);
                                $quizTitle = $a->quiz->title ?? 'Untitled Quiz';
                                $dateStr = optional($a->submitted_at ?? $a->started_at)->format('F j, Y g:i a');
                                $isPassed = !is_null($a->is_passed) ? (bool)$a->is_passed : ($percent >= 50);
                            @endphp
                            <tr class="border-bottom">
                                <td class="px-3">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $quizTitle }}</div>
                                    <div class="text-muted small">{{ $dateStr }}</div>
                                    <div class="text-muted small">
                                        Questions: {{ $totalQ }} | Total Marks: {{ number_format($totalMarks, 2) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $correct }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-danger">{{ $incorrect }}</span>
                                </td>
                                <td>
                                    {{ number_format($earned, 2) }} ({{ rtrim(rtrim(number_format($percent, 2), '0'), '.') }}%)
                                </td>
                                <td>
                                    <span class="badge {{ $isPassed ? 'bg-success' : 'bg-danger' }}">
                                        {{ $isPassed ? 'Pass' : 'Fail' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No attempts yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $attempts->withQueryString()->links() }}
    </div>
</div>
@endsection
