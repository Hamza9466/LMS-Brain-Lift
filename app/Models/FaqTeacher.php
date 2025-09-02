<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqTeacher extends Model
{
    use HasFactory;
        protected $fillable = ['question', 'resource'];

}