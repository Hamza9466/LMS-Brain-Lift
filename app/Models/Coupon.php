<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code','type','value','min_amount','max_uses','used_count','starts_at','ends_at','is_active'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
        'is_active' => 'boolean',
        'value'     => 'decimal:2',
        'min_amount'=> 'decimal:2',
    ];

    public function isValidFor(float $subtotal): bool
    {
        if (!$this->is_active) return false;

        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) return false;
        if ($this->ends_at   && $now->gt($this->ends_at))   return false;

        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) return false;
        if ($this->min_amount !== null && $subtotal < (float)$this->min_amount) return false;

        if (!in_array($this->type, ['percent','fixed'], true)) return false;

        return true;
    }

    public function discountAmount(float $subtotal): float
    {
        if ($subtotal <= 0) return 0.0;

        if ($this->type === 'fixed') {
            return (float) min((float)$this->value, $subtotal);
        }

        // percent
        $pct = max(0, min(100, (float)$this->value));
        return round($subtotal * ($pct / 100), 2);
    }

    // Optional: helps explain why a code failed
    public function invalidReason(float $subtotal): ?string
    {
        if (!$this->is_active) return 'Coupon is inactive';
        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) return 'Coupon not started yet';
        if ($this->ends_at && $now->gt($this->ends_at)) return 'Coupon expired';
        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) return 'Max uses reached';
        if ($this->min_amount !== null && $subtotal < (float)$this->min_amount) return 'Order total is below minimum';
        if (!in_array($this->type, ['percent','fixed'], true)) return 'Invalid coupon type';
        return null;
    }
}