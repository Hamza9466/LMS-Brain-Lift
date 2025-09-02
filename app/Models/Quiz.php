<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'course_id',
        'section_id',        // nullable is fine
        'title',
        'description',
        'duration_minutes',
        'max_attempts',
        'pass_percentage',
        'shuffle_questions',
        'shuffle_options',
        'is_published',
    ];

    // Parents
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function section()
    {
        // optional; works even if section_id is null
        return $this->belongsTo(Section::class);
    }

    // Children
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}