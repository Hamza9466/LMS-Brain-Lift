<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $fillable = [
        'user_id','status','currency','subtotal','discount','total',
        'gateway','gateway_ref','coupon_id','meta'
    ];

    protected $casts = ['meta' => 'array'];

    /* ---------------- Relations ---------------- */

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(\App\Models\OrderItem::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Coupon::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(\App\Models\Transaction::class);
    }

    /* ---------------- Key Action ---------------- */

    /**
     * Mark order as paid, optionally updating an existing transaction
     * (e.g., the proof you approved) or creating a new captured one.
     *
     * @param string      $gatewayRef    Reference/receipt from the payment
     * @param array       $payload       Extra metadata to store on transaction
     * @param string|null $gateway       Override gateway; defaults to $this->gateway
     * @param int|null    $sourceTxId    If provided, update that transaction instead of creating a new one
     */
    public function markPaid(string $gatewayRef, array $payload = [], ?string $gateway = null, ?int $sourceTxId = null): void
    {
        // 1) Mark the order paid + persist reference
        $this->update([
            'status'      => 'paid',
            'gateway_ref' => $gatewayRef,
        ]);

        // 2) Capture/record the transaction
        $gw = $gateway ?: $this->gateway;

        if ($sourceTxId) {
            // Update existing (e.g., user-submitted proof)
            $this->transactions()
                ->where('id', $sourceTxId)
                ->update([
                    'gateway'  => $gw,
                    'status'   => 'captured',
                    'amount'   => $this->total,
                    'currency' => $this->currency,
                    'reference'=> $gatewayRef,
                    'payload'  => array_merge($this->transactions()->find($sourceTxId)?->payload ?? [], $payload),
                ]);
        } else {
            // Create a fresh captured transaction
            $this->transactions()->create([
                'gateway'  => $gw,
                'status'   => 'captured',
                'amount'   => $this->total,
                'currency' => $this->currency,
                'reference'=> $gatewayRef,
                'payload'  => $payload,
            ]);
        }

        // 3) Enroll user in all purchased courses (pivot: course_user)
        $this->loadMissing('items');
        $now = now();

        foreach ($this->items as $it) {
            if (!$it->course_id) continue;

            // Use updateOrInsert to set purchased_at once and keep idempotency
            DB::table('course_user')->updateOrInsert(
                ['course_id' => $it->course_id, 'user_id' => $this->user_id],
                ['purchased_at' => $now, 'updated_at' => $now, 'created_at' => $now]
            );
        }

        // 4) Increment coupon usage if present
        if ($this->coupon_id) {
            $this->coupon()->increment('used_count');
        }
    }
}