<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscussionReply extends Model
{
    protected $fillable = ['thread_id','user_id','body'];

    public function thread() { return $this->belongsTo(DiscussionThread::class, 'thread_id'); }
    public function user() { return $this->belongsTo(User::class); }
}