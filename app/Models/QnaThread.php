<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QnaThread extends Model
{
    protected $fillable = [
        'course_id','lesson_id','user_id','title','body','status','is_pinned'
    ];

    public function replies(): HasMany {
        return $this->hasMany(QnaReply::class, 'thread_id')->latest();
    }

    public function user() { return $this->belongsTo(User::class); }
    public function course() { return $this->belongsTo(Course::class); }
    public function lesson() { return $this->belongsTo(Lesson::class); }
}