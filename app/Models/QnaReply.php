<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QnaReply extends Model
{
    protected $fillable = ['thread_id','user_id','body','is_answer'];

    public function thread() { return $this->belongsTo(QnaThread::class, 'thread_id'); }
    public function user() { return $this->belongsTo(User::class); }
}