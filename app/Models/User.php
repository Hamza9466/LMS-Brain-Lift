<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role', // enum: admin|student
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        // auto-hash on set (Laravel 10/11)
        'password' => 'hashed',
    ];

    
    // 🔹 Relationship for Admin
  public function adminDetail()
{
    return $this->hasOne(AdminDetail::class, 'user_id');
}

public function getFullNameAttribute(): string
{
    // Prefer the columns you actually have on users
    $first = trim((string) ($this->first_name ?? ''));
    $last  = trim((string) ($this->last_name ?? ''));
    $base  = trim($first . ' ' . $last);
    if ($base !== '') {
        return $base;
    }

    // If you DO have admin/teacher detail tables and they were eager-loaded,
    // use them WITHOUT triggering lazy queries.
    if ($this->relationLoaded('teacherDetail') && $this->teacherDetail) {
        return trim($this->teacherDetail->first_name . ' ' . $this->teacherDetail->last_name) ?: 'N/A';
    }
    if ($this->relationLoaded('adminDetail') && $this->adminDetail) {
        return trim($this->adminDetail->first_name . ' ' . $this->adminDetail->last_name) ?: 'N/A';
    }

    // Final fallbacks
    if (!empty($this->attributes['name']))  return (string) $this->attributes['name'];
    if (!empty($this->attributes['email'])) return (string) $this->attributes['email'];

    return 'N/A';
}

    // 🔹 Relationship for Teacher
    public function teacherDetail()
    {
        return $this->hasOne(\App\Models\TeacherDetail::class, 'user_id');
    }

    // 🔹 Relationship for Student
    public function studentDetail()
    {
        return $this->hasOne(\App\Models\StudentDetail::class, 'user_id');
    }

    public function enrolledCourses()
{
    return $this->belongsToMany(Course::class)
        ->withPivot(['purchased_at'])
        ->withTimestamps();
}


public function completedLessons() {
    return $this->belongsToMany(Lesson::class, 'lesson_user')->withPivot('completed_at')->withTimestamps();
}
public function taughtReviews()
    {
        return $this->hasManyThrough(
            CourseReview::class, // final
            Course::class,       // through
            'teacher_id',        // courses.teacher_id = users.id
            'course_id',         // course_reviews.course_id = courses.id
            'id',                // users.id
            'id'                 // courses.id
        );
    }
public function teacher()
{
    return $this->belongsTo(User::class, 'teacher_id');
}
 public function taughtStudentsCount(): int
    {
        return DB::table('course_user')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->where('courses.teacher_id', $this->id)
            ->distinct('course_user.user_id')
            ->count('course_user.user_id');
    }
      public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }
    public function personalDiscounts()
{
    return $this->hasMany(PersonalDiscount::class);
}
public function getDisplayNameAttribute()
{
    $n = trim(($this->first_name ?? '').' '.($this->last_name ?? ''));
    return $n !== '' ? $n : ($this->email ?? 'User #'.$this->id);
}
}