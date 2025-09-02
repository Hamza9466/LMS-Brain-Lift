<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    

    protected $fillable = ['course_id', 'title'];

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
 public function isUnlockedFor(User $user): bool
    {
        $course   = $this->course;
        $sections = $course->sections()->with('lessons')->orderBy('id')->get();

        $idx  = $sections->search(fn ($s) => $s->id === $this->id);
        $prev = $idx > 0 ? $sections[$idx - 1] : null;

        if (!$prev) return true; // first section always open

        // completed lessons
        $completedIds = DB::table('lesson_user')
            ->where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->pluck('lesson_id')
            ->toArray();

        $prevLessons     = $prev->lessons->sortBy('id')->values();
        $totalPrev       = $prevLessons->count();
        $donePrev        = $prevLessons->whereIn('id', $completedIds)->count();
        $isPrevFullyDone = ($totalPrev === 0) ? true : ($donePrev === $totalPrev);

        // quiz requirement only if previous section actually has a published quiz
        $hasPrevQuiz = Quiz::where('section_id', $prev->id)->where('is_published', true)->exists();

        $prevQuizPassed = true;
        if ($hasPrevQuiz) {
            $prevQuizPassed = DB::table('quiz_attempts')
                ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
                ->where('quiz_attempts.user_id', $user->id)
                ->where('quizzes.section_id',   $prev->id)
                ->where('quiz_attempts.is_passed', true)
                ->exists();
        }

        return $isPrevFullyDone && $prevQuizPassed;
    }

    /** First incomplete lesson for this user (or first if all done). */
    public function firstTargetLessonFor(User $user): ?Lesson
    {
        $lessons = $this->lessons()->orderBy('id')->get();
        if ($lessons->isEmpty()) return null;

        $completedIds = DB::table('lesson_user')
            ->where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->pluck('lesson_id')
            ->toArray();

        return $lessons->first(fn ($l) => !in_array($l->id, $completedIds)) ?? $lessons->first();
    }
}