<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_id','user_id','started_at','submitted_at','status',
        'score','percentage','is_passed','duration_seconds','ip_address'];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'is_passed' => 'boolean',
    ];

    public function quiz(){ 
        return $this->belongsTo(Quiz::class);
     }
    public function user(){
         return $this->belongsTo(User::class);
         }
    public function answers(){
         return $this->hasMany(QuizAnswer::class, 'attempt_id');
         }
}