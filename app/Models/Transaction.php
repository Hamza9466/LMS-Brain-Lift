<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
      protected $fillable = [
        'order_id','gateway','status','amount','currency','reference',
        'payload','proof_path','reviewed_by','reviewed_at','review_note'
    ];

    protected $casts = [
        'payload'     => 'array',
        'reviewed_at' => 'datetime',
    ];

      public function order()
    {
        return $this->belongsTo(Order::class);
    }
}