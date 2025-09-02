<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementUser extends Model
{
    // Use the plural pivot table you created
    protected $table = 'announcement_users';

    protected $fillable = ['announcement_id','user_id','read_at'];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}