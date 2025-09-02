<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class PersonalDiscount extends Model
{
    protected $fillable = [
        'course_id','user_id','type','value','active',
        'uses','max_uses','starts_at','ends_at',
    ];

    protected $casts = [
        'active'     => 'boolean',
        'starts_at'  => 'datetime',
        'ends_at'    => 'datetime',
        'value'      => 'decimal:2',  // Eloquent returns string; cast to float when using it
        'uses'       => 'integer',
        'max_uses'   => 'integer',
    ];

    /* ---------- Relations ---------- */
    public function user()   { return $this->belongsTo(User::class); }
    public function course() { return $this->belongsTo(Course::class); }

    /* ---------- State checks ---------- */
    public function isActive(): bool
    {
        if (!$this->active) return false;

        $now = Carbon::now();
        if ($this->starts_at && $now->lt($this->starts_at)) return false;
        if ($this->ends_at   && $now->gt($this->ends_at))   return false;

        if ($this->max_uses !== null && (int)($this->uses ?? 0) >= (int)$this->max_uses) {
            return false;
        }
        return true;
    }

    /* ---------- Scopes ---------- */
    public function scopeFor(Builder $q, int $userId, int $courseId): Builder
    {
        return $q->where('user_id', $userId)->where('course_id', $courseId);
    }

    public function scopeActive(Builder $q): Builder
    {
        $now = now();
        return $q->where('active', true)
            ->where(fn ($qq) => $qq->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
            ->where(fn ($qq) => $qq->whereNull('ends_at')->orWhere('ends_at', '>=', $now));
    }

    // NULL-safe uses check
    public function scopeUsable(Builder $q): Builder
    {
        // Prefer orWhereColumn when available
        return $q->where(function ($qq) {
            $qq->whereNull('max_uses')
               ->orWhereColumn('uses', '<', 'max_uses');
        });
    }

    // Handy finder you already use in controllers
    public static function activeForUserCourse(int $userId, int $courseId): Builder
    {
        return static::query()->for($userId, $courseId)->active()->usable();
    }

    /* ---------- Math helpers ---------- */

    /**
     * Return the DISCOUNT AMOUNT to subtract from a unit price.
     * (use this in totals)
     */
    public function discountAmountFor(float $price): float
    {
        $val = (float) $this->value;

        if ($this->type === 'percent') {
            $val = max(0, min(100, $val));
            return round($price * ($val / 100), 2);
        }

        // fixed amount
        return (float) min($price, max(0, $val));
    }

    /**
     * Apply discount and return the new price (optional helper).
     */
    public function applyTo(float $price): float
    {
        return max(0.0, round($price - $this->discountAmountFor($price), 2));
    }

    /**
     * Atomically increment uses (call this AFTER a paid/confirmed order).
     */
    public function redeem(): bool
    {
        if (!$this->isActive()) return false;

        $updated = static::whereKey($this->id)
            ->where(function ($q) {
                $q->whereNull('max_uses')
                  ->orWhereColumn('uses', '<', 'max_uses');
            })
            ->increment('uses');

        if ($updated) {
            $this->refresh();
            return true;
        }
        return false;
    }
}