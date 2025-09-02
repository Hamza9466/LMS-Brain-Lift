<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

protected $fillable = [
    'course_id','section_id','title','description','type',
    'video_path','video_file','pdf_path',
];
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
      public function getMediaPathAttribute()
    {
        return $this->type === 'video' ? $this->video_path : $this->pdf_path;
    }

  
public function completedBy() {
    return $this->belongsToMany(\App\Models\User::class, 'lesson_user')->withPivot('completed_at')->withTimestamps();
}
}