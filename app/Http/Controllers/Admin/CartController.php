<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coupon;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\PersonalDiscount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class CartController extends Controller
{
    /** View Cart page */
    public function index(Request $req)
    {
        $cart = $this->cart();
        [$totals, $ctx] = $this->computeTotals($cart, auth()->id());

        return view('website.pages.viewcart', [
            'cart'                   => $cart,
            'subtotal'               => $totals['subtotal'],
            'discount'               => $totals['personal_discount'] + $totals['coupon_discount'] + $totals['bundle_discount'],
            'total'                  => $totals['total'],
            'coupon'                 => $totals['coupon'],
            'coursesById'            => $ctx['coursesById'],
            'slugToId'               => $ctx['slugToId'],
            'personalDiscountTotal'  => $totals['personal_discount'],
            'couponDiscount'         => $totals['coupon_discount'],
            'bundleDiscount'         => $totals['bundle_discount'],
            'allCoursesFlag'         => $totals['all_courses'],
        ]);
    }

    /** Checkout page (GET) */
    public function checkoutPage()
    {
        $cart = $this->cart();
        if (empty($cart)) {
            return redirect()->route('cart.index')->withErrors('Your cart is empty.');
        }

        if (!auth()->check()) {
            return redirect()->route('login', ['intended' => route('cart.checkout')]);
        }

        [$totals] = $this->computeTotals($cart, auth()->id());

        return view('website.pages.checkout', [
            'cart'                  => session('cart', []),
            'subtotal'              => $totals['subtotal'],
            'total'                 => $totals['total'],
            'coupon'                => $totals['coupon'],
            'personalDiscountTotal' => $totals['personal_discount'],
            'couponDiscount'        => $totals['coupon_discount'],
            'bundleDiscount'        => $totals['bundle_discount'],
            'discount'              => $totals['personal_discount'] + $totals['coupon_discount'] + $totals['bundle_discount'],
            'allCoursesFlag'        => $totals['all_courses'],
        ]);
    }

    /** Remove a course from cart */
    public function remove(Request $req)
    {
        $data = $req->validate(['course_id' => ['required','integer']]);
        $cart = $this->cart();
        unset($cart[$data['course_id']]);
        session(['cart' => $cart]);
        session()->forget('coupon');
        // if user removed something, drop the "all courses" flag
        session()->forget('all_courses');
        return back()->with('success', 'Removed from cart.');
    }

    /** Apply coupon */
    public function applyCoupon(Request $req)
    {
        $req->validate(['code' => ['required','string','max:50']]);

        $cart = $this->cart();
        if (empty($cart)) return back()->withErrors(['code' => 'Cart is empty.']);

        [$totals] = $this->computeTotals($cart, auth()->id());
        $subtotal = $totals['subtotal'];

        $code   = strtoupper(trim($req->code));
        $coupon = Coupon::whereRaw('UPPER(code) = ?', [$code])->first();

        if (!$coupon) return back()->withErrors(['code' => 'Coupon not found']);

        if (!$coupon->isValidFor($subtotal)) {
            $reason = method_exists($coupon, 'invalidReason')
                ? $coupon->invalidReason($subtotal)
                : 'Invalid or ineligible coupon';
            session()->forget('coupon');
            return back()->withErrors(['code' => $reason]);
        }

        session(['coupon' => [
            'id'     => $coupon->id,
            'code'   => $coupon->code,
            'amount' => (float) $coupon->discountAmount($subtotal),
        ]]);

        return back()->with('success', 'Coupon applied.');
    }

    /* ---------------- Helpers ---------------- */

    private function cart(): array
    {
        return session('cart', []);
    }

    private function computeTotals(array $cart, ?int $userId): array
    {
        // Collect ids & slugs from cart
        $rawIds = collect($cart)->map(fn($r) => (int)($r['course_id'] ?? $r['id'] ?? 0))->filter();
        $slugs  = collect($cart)->map(fn($r) => $r['slug'] ?? null)->filter()->unique()->values();

        $coursesById   = $rawIds->isNotEmpty() ? Course::whereIn('id', $rawIds->all())->get() : collect();
        $coursesBySlug = $slugs->isNotEmpty()  ? Course::whereIn('slug', $slugs->all())->get() : collect();

        $allCourses = $coursesById->merge($coursesBySlug)->keyBy('id'); // id => Course
        $slugToId   = $coursesBySlug->keyBy('slug')->map->id;           // slug => id
        $courseIds  = $allCourses->keys()->map(fn($id)=>(int)$id)->values()->all();

        // Personal discounts (preload for this user across these courses)
        $discountsByCourse = collect();
        if ($userId && !empty($courseIds)) {
            $discountsByCourse = PersonalDiscount::where('user_id', $userId)
                ->whereIn('course_id', $courseIds)
                ->active()
                ->usable()
                ->get()
                ->keyBy('course_id');
        }

        $subtotal = 0.0;
        $personalDiscountTotal = 0.0;

        foreach ($cart as $row) {
            $qty = (int)($row['qty'] ?? 1);

            // resolve course id
            $cid = (int)($row['course_id'] ?? $row['id'] ?? 0);
            if ($cid === 0 && !empty($row['slug'])) {
                $cid = (int)($slugToId[$row['slug']] ?? 0);
            }

            // price
            $course    = $cid ? ($allCourses->get($cid) ?? null) : null;
            $unitPrice = (float)($row['price'] ?? ($course ? ($course->discount_price ?? $course->price ?? 0) : 0));
            $subtotal += $unitPrice * $qty;

            // personal discount (per unit)
            $unitPersonal = 0.0;
            if ($userId && $cid) {
                $pd = $discountsByCourse->get($cid)
                   ?? PersonalDiscount::activeForUserCourse($userId, $cid)->first();

                if ($pd && $pd->isActive()) {
                    if ($pd->type === 'percent') {
                        $p = max(0, min(100, (float)$pd->value));
                        $unitPersonal = round($unitPrice * ($p/100), 2);
                    } else { // amount
                        $unitPersonal = (float) min($unitPrice, max(0, (float)$pd->value));
                    }
                }
            }

            $personalDiscountTotal += $unitPersonal * $qty;
        }

        $subtotal              = round($subtotal, 2);
        $personalDiscountTotal = round($personalDiscountTotal, 2);

        // Coupon (revalidate against CURRENT subtotal)
        $couponSession  = session('coupon');
        $couponDiscount = 0.0;
        $couponOut      = null;

        if (!empty($couponSession['id'] ?? null)) {
            $c = Coupon::find($couponSession['id']);
            if ($c && $c->isValidFor($subtotal)) {
                $couponDiscount = (float) $c->discountAmount($subtotal);
                $couponOut = ['id'=>$c->id,'code'=>$c->code,'amount'=>$couponDiscount];
                session(['coupon' => $couponOut]);
            } else {
                session()->forget('coupon');
            }
        }

        // ------ All-courses bundle discount ------
        $bundleDiscount = 0.0;
        $allFlag = (bool) session('all_courses', false);

        // If the flag isn't set, but cart actually contains all active courses, enable it
        if (!$allFlag) {
            $activeIds = Course::when(Schema::hasColumn('courses','is_active'), fn($q)=>$q->where('is_active',1))
                ->pluck('id')->map(fn($i)=>(int)$i)->all();
            $cartIds = array_values(array_map(fn($r)=> (int)($r['course_id'] ?? $r['id'] ?? 0), $cart));
            $hasAll = !array_diff($activeIds, $cartIds) && count($activeIds) > 0;
            if ($hasAll) {
                $allFlag = true;
                session(['all_courses' => true]);
            }
        }

        if ($allFlag) {
            $bundleDiscount = round($subtotal * (float)config('website.pages.checkout', 0.20), 2);
        }

        $total = round(max($subtotal - ($personalDiscountTotal + $couponDiscount + $bundleDiscount), 0.0), 2);

        return [
            [
                'subtotal'          => $subtotal,
                'personal_discount' => $personalDiscountTotal,
                'coupon_discount'   => $couponDiscount,
                'bundle_discount'   => $bundleDiscount,
                'total'             => $total,
                'coupon'            => $couponOut,
                'all_courses'       => $allFlag,
            ],
            [
                'coursesById'       => $allCourses,
                'slugToId'          => $slugToId,
                'discountsByCourse' => $discountsByCourse,
            ],
        ];
    }
}