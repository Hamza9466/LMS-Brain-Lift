<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title','body','audience','course_id','is_published','created_by'
    ];

    public function course()   {
         return $this->belongsTo(Course::class);
         }
    public function creator()  {
         return $this->belongsTo(User::class, 'created_by');
         }
 public function recipients()
{
    return $this->belongsToMany(\App\Models\User::class, 'announcement_users') // <-- plural
                ->withTimestamps()
                ->withPivot('read_at');
}
}