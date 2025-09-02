<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseReview extends Model
{
    protected $fillable = [
        'course_id','user_id','rating','title','comment','is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
    public function user(): BelongsTo   { return $this->belongsTo(User::class); }

    // Scopes you may find handy
    public function scopeApproved($q) { return $q->where('is_approved', true); }
    public function scopeForCourse($q, int $courseId) { return $q->where('course_id', $courseId); }
}