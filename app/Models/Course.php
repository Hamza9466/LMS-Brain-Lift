<?php

namespace App\Models;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;
      protected $fillable = [
        'title','slug','short_description','description','long_description',
        'thumbnail','subject','promo_video_url',
        'level','language','total_lessons','total_duration_minutes',
        'what_you_will_learn','requirements','who_is_for','tags','materials',
        'is_free','price','compare_at_price','discount_percentage','discount_price','is_featured',
        'rating_avg','rating_count','rating_breakdown','enrollment_count',
        'status','published_at','teacher_id','created_by','category_id'
    ];

    protected $casts = [
        'is_free'             => 'boolean',
        'is_featured'         => 'boolean',
        'published_at'        => 'datetime',
        'price'               => 'decimal:2',
        'compare_at_price'    => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_price'      => 'decimal:2',
        'rating_avg'          => 'decimal:2',
        'what_you_will_learn' => 'array',
        'requirements'        => 'array',
        'who_is_for'          => 'array',
        'tags'                => 'array',
        'materials'           => 'array',
        'rating_breakdown'    => 'array',
    ];
    public function sections()
{
    return $this->hasMany(Section::class);
}
public function teacher()
{
    return $this->belongsTo(User::class, 'teacher_id');
}
 public function creator()
{
    return $this->belongsTo(\App\Models\User::class, 'created_by');
}

    // handy scope
   public function scopeForTeacher($query, $userId)
{
    return $query->where('teacher_id', $userId);
}


public function personalDiscounts()
{
    return $this->hasMany(PersonalDiscount::class);
}
// relations
public function reviews()
{
    return $this->hasMany(CourseReview::class);
}

/** Recalculate rating stats after review create/update/delete */
public function recalculateRatings(): void
{
    $total = (int) $this->reviews()->where('is_approved', true)->count();
    $avg   = (float) ($this->reviews()->where('is_approved', true)->avg('rating') ?? 0);

    // breakdown counts
    $rows = $this->reviews()
        ->where('is_approved', true)
        ->selectRaw('rating, COUNT(*) as c')
        ->groupBy('rating')
        ->pluck('c','rating')
        ->all();

    $counts = [1=>0,2=>0,3=>0,4=>0,5=>0];
    foreach ($rows as $r => $c) { $counts[(int)$r] = (int)$c; }

    // convert to percentages for UI
    $pct = [];
    foreach ([5,4,3,2,1] as $star) {
        $pct[(string)$star] = $total ? (int) round($counts[$star] * 100 / $total) : 0;
    }

    $this->rating_count     = $total;
    $this->rating_avg       = round($avg, 2);
    $this->rating_breakdown = $pct;
    $this->save();
}
 public function approvedReviews()
    {
        return $this->hasMany(CourseReview::class)->where('is_approved', true);
    }

      public function students()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['purchased_at'])
            ->withTimestamps();
    }

        public function lessons()
    {
        return $this->hasManyThrough(
            Lesson::class,   
            Section::class,  
            'course_id',     
            'section_id'     
        );
    }


   public function category()
{
    return $this->belongsTo(CourseCategory::class, 'category_id');
}

public function personalDiscountForUser(?int $userId): ?PersonalDiscount
{
    if (!$userId) return null;
    return \App\Models\PersonalDiscount::active()
        ->usable()
        ->for($userId, $this->id)
        ->orderByDesc('value')   // if multiple, take the largest
        ->first();
}

public function discountAmountForUser(?int $userId): float
{
    if ($this->is_free || !$this->price) return 0.0;

    $pd = $this->personalDiscountForUser($userId);
    if (!$pd) return 0.0;

    if ($pd->type === 'percent') {
        return round((float)$this->price * ((float)$pd->value / 100), 2);
    }

    // fixed amount
    return (float) min((float)$this->price, (float)$pd->value);
}

public function priceForUser(?int $userId): float
{
    return max(0, (float)$this->price - $this->discountAmountForUser($userId));
}


}