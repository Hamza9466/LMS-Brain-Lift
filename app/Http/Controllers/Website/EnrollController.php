<?php

namespace App\Http\Controllers\Website;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class EnrollController extends Controller
{
    /** Show enroll page with Register/Login tabs */
    public function show(Course $course)
    {
        return view('website.pages.enroll-start', compact('course'));
    }

    /** Register then log in; if not from cart, add chosen course or ALL courses; go to checkout */
    public function register(Request $request)
    {
        // Keep the Register tab active if validation fails
        session()->flash('auth_tab', 'register');

        $fromCart = $request->boolean('from_cart');

        // Normalize checkbox -> 1 or null so "required_without:all_courses" works reliably
        $request->merge(['all_courses' => $request->has('all_courses') ? 1 : null]);

        $validated = $request->validate([
            'first_name'  => ['required','string','max:100'],
            'last_name'   => ['required','string','max:100'],
            'email'       => ['required','email','max:190', Rule::unique('users','email')],
            'password'    => ['required','min:6','confirmed'],

            // key: course_id NOT required when all_courses is present
            'all_courses' => ['nullable','in:1'],
            'course_id'   => ['required_without:all_courses','nullable','integer','exists:courses,id'],

            // optional
            'from_cart'   => ['nullable'],
        ]);

        // Create & log in user
        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']), // safe even if you also use 'hashed' cast
            // 'role'    => 'student', // uncomment if you store a role
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        // Build cart intent
        if ($request->boolean('all_courses')) {
            $this->replaceCartWithAllCourses();
            session(['all_courses' => true]);
        } else {
            session()->forget('all_courses');
            if (!$fromCart && $request->filled('course_id')) {
                $this->addCourseToCart((int) $request->course_id);
            }
        }

        return redirect()->route('cart.checkout')->with('success', 'Welcome! Complete your checkout.');
    }

    public function login(Request $request)
    {
        // Keep the Login tab active if validation fails
        session()->flash('auth_tab', 'login');

        $fromCart = $request->boolean('from_cart');

        // Normalize checkbox -> 1 or null so "required_without:all_courses" works reliably
        $request->merge(['all_courses' => $request->has('all_courses') ? 1 : null]);

        $validated = $request->validate([
            'email'       => ['required','email'],
            'password'    => ['required'],

            // key: course_id NOT required when all_courses is present
            'all_courses' => ['nullable','in:1'],
            'course_id'   => ['required_without:all_courses','nullable','integer','exists:courses,id'],

            // optional
            'from_cart'   => ['nullable'],
        ]);

        if (!Auth::attempt($request->only('email','password'), false)) {
            return back()
                ->with('tab', 'login')           // your existing flag
                ->withErrors(['email' => 'Invalid email or password.'])
                ->withInput();
        }

        $request->session()->regenerate();

        // Build cart intent
        if ($request->boolean('all_courses')) {
            $this->replaceCartWithAllCourses();
            session(['all_courses' => true]);
        } else {
            session()->forget('all_courses');
            if (!$fromCart && $request->filled('course_id')) {
                $this->addCourseToCart((int) $request->course_id);
            }
        }

        return redirect()->route('cart.checkout')->with('success', 'Signed in. Complete your checkout.');
    }

    /** Already-authenticated -> add course to cart -> checkout */
    public function proceed(Request $request)
    {
        $request->validate([
            'course_id' => ['required','integer','exists:courses,id'],
        ]);

        $this->addCourseToCart((int) $request->course_id);

        return redirect()->route('cart.checkout');
    }

    /* --------------- Helpers --------------- */

    private function addCourseToCart(int $courseId): void
    {
        $course = Course::find($courseId);
        if (!$course) return;

        $cart = session('cart', []);

        if (isset($cart[$course->id])) {
            $cart[$course->id]['qty'] = (int)($cart[$course->id]['qty'] ?? 1) + 1;
        } else {
            $cart[$course->id] = [
                'id'        => $course->id,
                'course_id' => $course->id,
                'slug'      => $course->slug ?? null,
                'thumbnail' => $course->thumbnail_url ?? $course->thumbnail ?? null,
                'title'     => $course->title,
                'qty'       => 1,
                'price'     => (float) ($course->discount_price ?? $course->price ?? 0),
            ];
        }

        session(['cart' => $cart]);
        session()->forget('coupon'); // avoid stale totals
    }

    /** Replace cart with ALL active courses */
    private function replaceCartWithAllCourses(): void
    {
        $courses = Course::when(
            Schema::hasColumn('courses','is_active'),
            fn($q) => $q->where('is_active', 1)
        )->get();

        $cart = [];
        foreach ($courses as $c) {
            $cart[$c->id] = [
                'id'        => $c->id,
                'course_id' => $c->id,
                'slug'      => $c->slug ?? null,
                'thumbnail' => $c->thumbnail_url ?? $c->thumbnail ?? null,
                'title'     => $c->title,
                'qty'       => 1,
                'price'     => (float) ($c->discount_price ?? $c->price ?? 0),
            ];
        }

        session(['cart' => $cart]);
        session()->forget('coupon');
    }
}