<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PersonalDiscount;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;

class CheckoutController extends Controller
{
    public function buyNow(Request $req, Course $course)
    {
        $cart = [
            $course->id => [
                'course_id' => $course->id,
                'title'     => $course->title,
                'qty'       => 1,
                'price'     => (float)($course->discount_price ?? $course->price ?? 0),
            ],
        ];
        session(['cart' => $cart]);
        session()->forget('coupon');
        session()->forget('all_courses'); // single course - not bundle

        return redirect()->route('cart.index')->with('success', 'Ready to checkout.');
    }

    public function fakeCheckout(Request $req)
    {
        // allow your select value (FaysalBank) OR map option value to 'bank'
        $validated = $req->validate([
            'gateway' => ['required', Rule::in(['jazzcash','easypaisa','bank','FaysalBank'])],
        ]);
        $gateway = $validated['gateway'] === 'FaysalBank' ? 'bank' : $validated['gateway'];

        $cart = session('cart', []);
        if (empty($cart)) return back()->withErrors('Cart is empty');

        $userId   = auth()->id();
        $currency = env('APP_CURRENCY','USD');
        $now      = now();

        $subtotal = 0.0; $personalDiscountTotal = 0.0;
        foreach ($cart as $row) {
            $qty       = (int)($row['qty'] ?? 1);
            $unitPrice = (float)($row['price'] ?? 0);
            $subtotal += $unitPrice * $qty;

            $course = Course::find($row['course_id'] ?? null);
            if (!$course || !$userId) continue;

            $pd = PersonalDiscount::where('course_id', $course->id)
                ->where('user_id', $userId)
                ->when(Schema::hasColumn('personal_discounts','active'), fn($q)=>$q->where('active', true))
                ->where(fn($q)=>$q->whereNull('starts_at')->orWhere('starts_at','<=',$now))
                ->where(fn($q)=>$q->whereNull('ends_at')->orWhere('ends_at','>=',$now))
                ->where(fn($q)=>$q->whereNull('max_uses')->orWhereColumn('uses','<','max_uses'))
                ->orderByDesc('value')
                ->first();

            $unitPersonal = 0.0;
            if ($pd) {
                if (Schema::hasColumn('personal_discounts','type') && Schema::hasColumn('personal_discounts','value')) {
                    $unitPersonal = $pd->type === 'percent'
                        ? round($unitPrice * ((float)$pd->value / 100), 2)
                        : (float) min($unitPrice, (float)$pd->value);
                } elseif (Schema::hasColumn('personal_discounts','percentage')) {
                    $unitPersonal = round($unitPrice * ((float)$pd->percentage / 100), 2);
                }
            }
            $personalDiscountTotal += $unitPersonal * $qty;
        }

        // Coupon
        $coupon         = session('coupon');
        $couponDiscount = (float)($coupon['amount'] ?? 0);

        // All-courses bundle
        $bundleDiscount = 0.0;
        if (session('all_courses')) {
            $bundleDiscount = round($subtotal * (float)config('site.all_courses_discount', 0.20), 2);
        }

        $discount = $personalDiscountTotal + $couponDiscount + $bundleDiscount;
        $total    = max($subtotal - $discount, 0);

        $order = Order::create([
            'user_id'  => $userId,
            'status'   => 'pending',
            'currency' => $currency,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total'    => $total,
            'gateway'  => $gateway,
            'coupon_id'=> $coupon['id'] ?? null,
            'meta'     => [
                'cart'                  => $cart,
                'coupon'                => $coupon,
                'personal_discount_sum' => $personalDiscountTotal,
                'bundle_discount'       => $bundleDiscount,
                'all_courses'           => (bool) session('all_courses', false),
            ],
        ]);

        foreach ($cart as $row) {
            OrderItem::create([
                'order_id'  => $order->id,
                'course_id' => $row['course_id'],
                'price'     => (float)($row['price'] ?? 0),
            ]);
        }

        $tx = Transaction::create([
            'order_id'  => $order->id,
            'gateway'   => $gateway,
            'status'    => 'pending',
            'amount'    => $total,
            'currency'  => $currency,
            'reference' => 'PENDING-'.now()->timestamp,
            'payload'   => ['note' => 'Awaiting proof upload'],
        ]);

        return redirect()->route('checkout.proof', $tx)
            ->with('success', 'Please upload your payment screenshot to complete the order.');
    }

    public function proofForm(Transaction $transaction)
    {
        abort_unless(optional($transaction->order)->user_id === auth()->id(), 403);
        $order = $transaction->order()->with('items')->first();
        return view('website.pages.checkout-proof', compact('transaction','order'));
    }

    public function proofStore(Request $req, Transaction $transaction)
    {
        abort_unless(optional($transaction->order)->user_id === auth()->id(), 403);

        $data = $req->validate([
            'proof'       => ['required','file','mimes:jpg,jpeg,png,webp,pdf','max:5120'],
            'payer_name'  => ['nullable','string','max:120'],
            'payer_phone' => ['nullable','string','max:60'],
            'paid_at'     => ['nullable','date'],
            'reference'   => ['nullable','string','max:120'],
            'note'        => ['nullable','string','max:500'],
        ]);

        $path = $req->file('proof')->store('payment-proofs', 'public');

        $payload = $transaction->payload ?? [];
        $payload['user_submission'] = [
            'proof_path'  => $path,
            'payer_name'  => $data['payer_name']  ?? null,
            'payer_phone' => $data['payer_phone'] ?? null,
            'paid_at'     => $data['paid_at']     ?? null,
            'user_ref'    => $data['reference']   ?? null,
            'note'        => $data['note']        ?? null,
        ];

        $transaction->update([
            'status'     => 'submitted',
            'proof_path' => $path,
            'payload'    => $payload,
        ]);

        return redirect()->route('cart.index')
            ->with('success', 'Payment proof uploaded. We will review and approve shortly.');
    }
}