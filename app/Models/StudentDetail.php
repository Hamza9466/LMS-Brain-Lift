<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDetail extends Model
{
    use HasFactory;

  protected $fillable = [
    'user_id',
    'first_name',
    'last_name',
    'username',
    'profile_image',
    'phone',
    'gender',
    'dob',
    'address',
    'city',
    'country',
    'institute_name',
    'program_name',
    'enrollment_year',
];


    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}