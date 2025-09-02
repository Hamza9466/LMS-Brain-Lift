<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiscussionThread extends Model
{
    protected $fillable = ['course_id','lesson_id','user_id','title','body','is_pinned'];

    public function replies(): HasMany {
        return $this->hasMany(DiscussionReply::class, 'thread_id')->oldest();
    }

    public function user() { return $this->belongsTo(User::class); }
    public function course() { return $this->belongsTo(Course::class); }
    public function lesson() { return $this->belongsTo(Lesson::class); }
}