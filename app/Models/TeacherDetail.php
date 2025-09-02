<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeacherDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'username',
        'phone', 'profile_image', 'qualification',
        'experience', 'specialization', 'bio'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
public function teacher()
{
    return $this->belongsTo(\App\Models\User::class, 'teacher_id');
}
}