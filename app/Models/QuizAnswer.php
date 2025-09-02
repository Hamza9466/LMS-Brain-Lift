<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    use HasFactory;

     protected $fillable = ['attempt_id','question_id','selected_option_ids','points_awarded'];
    protected $casts = ['selected_option_ids' => 'array'];

    public function attempt(){
         return $this->belongsTo(QuizAttempt::class, 'attempt_id');
         }
    public function question(){
         return $this->belongsTo(QuizQuestion::class, 'question_id');
         }
}