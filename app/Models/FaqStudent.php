<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqStudent extends Model
{
    use HasFactory;

        protected $fillable = ['question', 'resource'];

}