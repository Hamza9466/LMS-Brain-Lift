<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = ['quiz_id','type','text','points','display_order'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    // 👇 tell Eloquent the FK is question_id (not quiz_question_id)
    public function options()
    {
        return $this->hasMany(QuizOption::class, 'question_id');
    }

    public function correctOptionIds(): array
    {
        return $this->options()->where('is_correct', 1)->pluck('id')->all();
    }

    
}