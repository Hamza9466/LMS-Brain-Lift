<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $fillable = [
        'user_id','title','body','link_url','read_at','created_by'
    ];

    public function user()   {
         return $this->belongsTo(User::class); 
        }
    public function creator(){ 
        return $this->belongsTo(User::class, 'created_by');
     }
}