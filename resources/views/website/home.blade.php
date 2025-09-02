
<!-- ==================== Header End Here ==================== -->
@extends('website.layouts.main')
@section('content')
    <!-- ========================= Banner Section Start =============================== -->
{{-- resources/views/website/partials/home-banner-register.blade.php --}}
{{-- resources/views/website/partials/home-auth-card.blade.php (or inline on your page) --}}
<section class="banner py-80 position-relative overflow-hidden">
    {{-- Background shapes (keep your assets) --}}
    <img src="{{ asset('assets/website/images/home/shape1.png') }}" alt="" class="shape one animation-rotation">
    <img src="{{ asset('assets/website/images/home/shape2.png') }}" alt="" class="shape two animation-scalation">
    <img src="{{ asset('assets/website/images/home/shape3.png') }}" alt="" class="shape three animation-walking">
    <img src="{{ asset('assets/website/images/home/shape4.png') }}" alt="" class="shape four animation-scalation">
    <img src="{{ asset('assets/website/images/home/shape5.png') }}" alt="" class="shape five animation-walking">

    @php
        // Expect $courses from controller; safe fallback here too
        if (!isset($courses)) {
            try {
                $courses = \App\Models\Course::select('id','title','price','discount_price')
                    ->orderBy('title')->get();
            } catch (\Throwable $e) {
                $courses = collect();
            }
        }

        // Read discount (set in config/site.php). Supports either key.
        $allCoursesDiscount = (float) config('site.all_courses_discount', 0.20); // 0.20 = 20%
        $allCoursesDiscountPct = (int) round(
            config('site.all_courses_discount_pct', $allCoursesDiscount * 100)
        );

        $activeTab = old('_auth_tab', session('auth_tab', 'register')); // 'register' or 'login'

        // Currency label (optional)
        $currency = config('app.currency_symbol', 'PKR');
    @endphp

    <div class="container">
        {{-- Alerts --}}
        @if (session('success')) <div class="alert alert-success mb-32">{{ session('success') }}</div> @endif
        @if (session('error'))   <div class="alert alert-danger  mb-32">{{ session('error') }}</div>   @endif
        @if ($errors->any())
            <div class="alert alert-danger mb-32">
                <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="row gy-5 align-items-center">
            {{-- Left: copy --}}
            <div class="col-xl-6">
                <div class="banner-content pe-md-4">
                    <div class="flex-align gap-8 mb-16" data-aos="fade-down">
                        <span class="w-8 h-8 bg-main-600 rounded-circle"></span>
                        <h5 class="text-main-600 mb-0">Your Future, Achieve Success</h5>
                    </div>

                    <h1 class="display2 mb-24 wow bounceInLeft">
                        Find Your <span class="text-main-two-600 wow bounceInRight" data-wow-duration="2s" data-wow-delay=".5s">Ideal</span>
                        Course, Build <span class="text-main-600 wow bounceInUp" data-wow-duration="1s" data-wow-delay=".5s">Skills</span>
                    </h1>
                    <p class="text-neutral-500 text-line-2 wow bounceInUp">
                        Welcome to Brain Lift, where learning meets opportunity. Whether you’re a student, freelancer,
                        or professional, we help you gain practical skills and grow your career.
                    </p>

                    <div class="buttons-wrapper flex-align flex-wrap gap-24 mt-40">
                        <a href="{{ route('CourseGrid') }}" class="btn btn-main rounded-pill flex-align gap-8" data-aos="fade-right">
                            Explore Courses
                            <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                        </a>
                        <a href="{{ route('about') }}" class="btn btn-outline-main rounded-pill flex-align gap-8" data-aos="fade-left">
                            Why Brain Lift ?
                            <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right: Auth card with tabs --}}
            <div class="col-xl-6">
                <div class="position-relative" style="max-width: 580px; margin-left:auto;">
                    <div class="card border-0 shadow-lg rounded-4 bg-white wow bounceIn" data-wow-duration="1.2s" data-wow-delay=".2s">
                        <div class="card-body p-24 p-md-4">

                            <div class="d-flex justify-content-between align-items-end mb-3">
                                <div>
                                    <h3 class="mb-6">Welcome</h3>
                                    <p class="text-neutral-500 mb-6">Register or login and continue to checkout.</p>
                                </div>
                            </div>

                            {{-- Tabs header --}}
                            <ul class="nav nav-tabs mt-3 mb-6" id="homeAuthTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $activeTab==='register' ? 'active' : '' }}"
                                            id="tab-register-btn"
                                            data-bs-toggle="tab" data-bs-target="#tab-register" type="button" role="tab">
                                        Register
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $activeTab==='login' ? 'active' : '' }}"
                                            id="tab-login-btn"
                                            data-bs-toggle="tab" data-bs-target="#tab-login" type="button" role="tab">
                                        Login
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content pt-3" id="homeAuthTabsContent">
                                {{-- REGISTER --}}
                                <div class="tab-pane fade {{ $activeTab==='register' ? 'show active' : '' }}" id="tab-register" role="tabpanel">
                                    <form method="POST" action="{{ route('enroll.register') }}" class="row g-3" novalidate>
                                        @csrf
                                        <input type="hidden" name="_auth_tab" value="register">
                                        <input type="hidden" name="from_cart" value="0">

                                        {{-- All Courses switch --}}
                                        <div class="col-12">
                                            <div class="form-check form-switch d-flex align-items-center gap-2 mb-5 ">
                                                <input class="form-check-input text-black border-1 border-black" type="checkbox" id="all_courses_register" name="all_courses" value="1"
                                                       {{ old('all_courses') ? 'checked' : '' }}>
                                                <h6 class="form-check-label pt-6" for="all_courses_register" style="margin-top:10px; margin-left: 20px;">
                                                    Enroll in <strong>All Courses</strong> (save {{ 56 }}%)
                                                </h6>
                                            </div>
                                            <label class="form-label fw-medium">Select Course</label>
                                            <select name="course_id" id="course_id_register" class="form-select form-select-lg" required>
                                                <option value="" selected disabled>Choose a course…</option>
                                                @forelse ($courses as $c)
                                                    @php $price = (float)($c->discount_price ?? $c->price ?? 0); @endphp
                                                    <option
                                                        value="{{ $c->id }}"
                                                        data-price="{{ $price }}"
                                                        {{ old('course_id') == $c->id ? 'selected' : '' }}>
                                                        {{ $c->title }}
                                                    </option>
                                                @empty
                                                    <option value="" disabled>No courses available</option>
                                                @endforelse
                                            </select>
                                            @error('course_id') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        {{-- Names --}}
                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">First name</label>
                                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control form-control-lg" required>
                                            @error('first_name') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Last name</label>
                                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control form-control-lg" required>
                                            @error('last_name') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        {{-- Email / Password --}}
                                        <div class="col-12">
                                            <label class="form-label fw-medium">Email</label>
                                            <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg" required>
                                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Password</label>
                                            <input type="password" name="password" class="form-control form-control-lg" required>
                                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Confirm Password</label>
                                            <input type="password" name="password_confirmation" class="form-control form-control-lg" required>
                                            @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        {{-- Summary (UI only) --}}
                                        <div class="col-12">
                                            <div id="priceSummary" class="alert alert-light border d-none">
                                                <div class="d-flex justify-content-between"><span>Subtotal</span><strong><span id="sumSubtotal">0</span> {{ $currency }}</strong></div>
                                                <div class="d-flex justify-content-between"><span>Discount</span><strong><span id="sumDiscount">0</span> {{ $currency }}</strong></div>
                                                <hr class="my-2">
                                                <div class="d-flex justify-content-between"><span>Total</span><strong><span id="sumTotal">0</span> {{ $currency }}</strong></div>
                                            </div>
                                        </div>

                                        <div class="col-12 pt-2">
                                            <button type="submit" class="btn btn-main rounded-pill w-100">
                                                Create account & Continue
                                                <i class="ph-bold ph-arrow-up-right d-inline-flex ms-2"></i>
                                            </button>
                                        </div>
                                    </form>

                                    <div class="mt-16 text-center">
                                        <span class="text-neutral-500">Already have an account?</span>
                                        <a href="#tab-login" class="fw-semibold text-main-600" id="switchToLogin">Login</a>
                                    </div>
                                </div>

                                {{-- LOGIN --}}
                                <div class="tab-pane fade {{ $activeTab==='login' ? 'show active' : '' }}" id="tab-login" role="tabpanel">
                                    <form method="POST" action="{{ route('enroll.login') }}" class="row g-3" novalidate>
                                        @csrf
                                        <input type="hidden" name="_auth_tab" value="login">
                                        <input type="hidden" name="from_cart" value="0">

                                        {{-- All Courses switch --}}
                                        <div class="col-12">
                                            <div class="form-check form-switch d-flex align-items-center gap-2 mb-2">
                                                <input class="form-check-input text-black border-1 border-black" type="checkbox" id="all_courses_login" name="all_courses" value="1">
                                                <h6 class="form-check-label" for="all_courses_login" style="margin-top:17px; margin-left: 20px;">
                                                    Enroll in <strong>All Courses</strong> (save {{ 56 }}%)
                                                </h6>
                                            </div>

                        <label class="form-label fw-medium">Select Course</label>
                        <select name="course_id" id="course_id_login" class="form-select form-select-lg" required>
                            <option value="" selected disabled>Choose a course…</option>
                            @foreach ($courses as $c)
                                @php $price = (float)($c->discount_price ?? $c->price ?? 0); @endphp
                                <option value="{{ $c->id }}" data-price="{{ $price }}">{{ $c->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-medium">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg" required>
                    </div>

                    {{-- Summary (UI only) mirrors register --}}
                    <div class="col-12">
                        <div id="priceSummaryLogin" class="alert alert-light border d-none">
                            <div class="d-flex justify-content-between"><span>Subtotal</span><strong><span id="sumSubtotalLogin">0</span> {{ $currency }}</strong></div>
                            <div class="d-flex justify-content-between"><span>Discount</span><strong><span id="sumDiscountLogin">0</span> {{ $currency }}</strong></div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between"><span>Total</span><strong><span id="sumTotalLogin">0</span> {{ $currency }}</strong></div>
                        </div>
                    </div>

                    <div class="col-12 pt-2">
                        <button type="submit" class="btn btn-primary rounded-pill w-100">
                            Login & Continue
                            <i class="ph-bold ph-arrow-up-right d-inline-flex ms-2"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-16 text-center">
                    <span class="text-neutral-500">New here?</span>
                    <a href="#tab-register" class="fw-semibold text-main-600" id="switchToRegister">Create an account</a>
                </div>
            </div>
        </div> {{-- /.tab-content --}}

    </div>
</div>
</div>
</div>
</div>

{{-- Tabs + sync + pricing --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ===== Tabs (works even without Bootstrap JS) =====
    (function () {
        const buttons = Array.from(document.querySelectorAll('[data-bs-toggle="tab"]'));
        if (!buttons.length) return;
        function activate(targetSel, btn) {
            const panes = document.querySelectorAll('.tab-pane');
            buttons.forEach(b => { b.classList.remove('active'); b.setAttribute('aria-selected','false'); });
            panes.forEach(p => p.classList.remove('show', 'active'));
            btn.classList.add('active'); btn.setAttribute('aria-selected','true');
            const pane = document.querySelector(targetSel); if (pane) pane.classList.add('show','active');
        }
        buttons.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const target = btn.getAttribute('data-bs-target'); if (!target) return;
                activate(target, btn);
                if (history.replaceState) history.replaceState(null, '', target); else location.hash = target;
            });
        });
        if (location.hash === '#tab-login') document.getElementById('tab-login-btn')?.click();
    })();

    // Elements
    const regSel = document.getElementById('course_id_register');
    const logSel = document.getElementById('course_id_login');
    const allReg = document.getElementById('all_courses_register');
    const allLog = document.getElementById('all_courses_login');

    // UI summaries
    const sumBoxReg = document.getElementById('priceSummary');
    const sumSubReg = document.getElementById('sumSubtotal');
    const sumDisReg = document.getElementById('sumDiscount');
    const sumTotReg = document.getElementById('sumTotal');

    const sumBoxLog = document.getElementById('priceSummaryLogin');
    const sumSubLog = document.getElementById('sumSubtotalLogin');
    const sumDisLog = document.getElementById('sumDiscountLogin');
    const sumTotLog = document.getElementById('sumTotalLogin');

    // Data
@php
    // Build a clean PHP array first (no arrow fn inside @json)
    $COURSES = $courses->map(function ($c) {
        return [
            'id'    => $c->id,
            'title' => $c->title,
            'price' => (float) ($c->discount_price ?? $c->price ?? 0),
        ];
    })->values()->all();
@endphp
    const DISCOUNT = {{ json_encode((float)$allCoursesDiscount) }}; // e.g. 0.2

    function fmt(n) {
        try {
            return (Math.round((n + Number.EPSILON) * 100) / 100).toLocaleString();
        } catch (_) { return n; }
    }

    function sumAll() {
        return COURSES.reduce((a,c) => a + (Number(c.price)||0), 0);
    }

    function priceOf(id) {
        const c = COURSES.find(x => String(x.id) === String(id));
        return c ? Number(c.price) || 0 : 0;
    }

    function updateSummary(isRegister) {
        const allBox = isRegister ? allReg : allLog;
        const sel    = isRegister ? regSel : logSel;
        const box    = isRegister ? sumBoxReg : sumBoxLog;
        const elSub  = isRegister ? sumSubReg : sumSubLog;
        const elDis  = isRegister ? sumDisReg : sumDisLog;
        const elTot  = isRegister ? sumTotReg : sumTotLog;

        let subtotal = 0, discount = 0, total = 0;

        if (allBox?.checked) {
            subtotal = sumAll();
            discount = subtotal * DISCOUNT;
            total    = subtotal - discount;
            box?.classList.remove('d-none');
        } else if (sel && sel.value) {
            subtotal = priceOf(sel.value);
            discount = 0;
            total    = subtotal;
            box?.classList.remove('d-none');
        } else {
            box?.classList.add('d-none');
        }

        if (elSub) elSub.textContent = fmt(subtotal);
        if (elDis) elDis.textContent = fmt(discount);
        if (elTot) elTot.textContent = fmt(Math.max(total, 0));
    }

    function applyAllCoursesState(src, other, selA, selB) {
        const checked = !!src?.checked;
        if (checked) {
            [selA, selB].forEach(sel => {
                if (!sel) return;
                sel.value = '';
                sel.disabled = true;
                sel.required = false;
            });
        } else {
            [selA, selB].forEach(sel => {
                if (!sel) return;
                sel.disabled = false;
                sel.required = true;
            });
        }
        if (other) other.checked = checked;
        updateSummary(true);
        updateSummary(false);
    }

    // Events
    allReg?.addEventListener('change', () => applyAllCoursesState(allReg, allLog, regSel, logSel));
    allLog?.addEventListener('change', () => applyAllCoursesState(allLog, allReg, regSel, logSel));

    function sync(from, to) {
        if (!from || !to) return;
        if (allReg?.checked || allLog?.checked) return; // not when in "all" mode
        to.value = from.value || '';
        updateSummary(true);
        updateSummary(false);
    }

    regSel?.addEventListener('change', () => { sync(regSel, logSel); updateSummary(true); });
    logSel?.addEventListener('change', () => { sync(logSel, regSel); updateSummary(false); });

    // Initial state
    if (allReg?.checked || allLog?.checked) {
        applyAllCoursesState(allReg?.checked ? allReg : allLog, allReg?.checked ? allLog : allReg, regSel, logSel);
    }
    sync(regSel, logSel);
    updateSummary(true);
    updateSummary(false);

    // Switch links
    const switchToLogin    = document.getElementById('switchToLogin');
    const switchToRegister = document.getElementById('switchToRegister');
    switchToLogin?.addEventListener('click',  e => { e.preventDefault(); document.getElementById('tab-login-btn')?.click(); });
    switchToRegister?.addEventListener('click', e => { e.preventDefault(); document.getElementById('tab-register-btn')?.click(); });
});
</script>
</section>



<!-- ========================= Banner SEction End =============================== -->

    <!-- ========================== Brand Section Start =========================== -->
 <div class="brand wow fadeInUpBig" data-wow-duration="1s" data-wow-delay=".5s">
    <div class="container container--lg">
        <div class="brand-box py-80 px-16 bg-main-25 border border-neutral-30 rounded-16">
            <h5 class="mb-40 text-center text-neutral-500">TRUSTED BY OVER 17,300 GREAT TEAMS</h5>
            <div class="container">
                <div class="brand-slider">
  <div class="brand-slider__item px-24">
    <img src="{{ asset('assets/website/images/home/brand-img1.png') }}" alt="Brand 1">
  </div>
  <div class="brand-slider__item px-24">
    <img src="{{ asset('assets/website/images/home/brand-img2.png') }}" alt="Brand 2">
  </div>
  <div class="brand-slider__item px-24">
    <img src="{{ asset('assets/website/images/home/brand-img3.png') }}" alt="Brand 3">
  </div>
  <div class="brand-slider__item px-24">
    <img src="{{ asset('assets/website/images/home/brand-img4.png') }}" alt="Brand 4">
  </div>
  <div class="brand-slider__item px-24">
    <img src="{{ asset('assets/website/images/home/brand-img5.png') }}" alt="Brand 5">
  </div>
  <div class="brand-slider__item px-24">
    <img src="{{ asset('assets/website/images/home/brand-img6.png') }}" alt="Brand 6">
  </div>
  <div class="brand-slider__item px-24">
    <img src="{{ asset('assets/website/images/home/brand-img7.png') }}" alt="Brand 7">
  </div>
  <div class="brand-slider__item px-24">
    <img src="{{ asset('assets/website/images/home/brand-img3.png') }}" alt="Brand 3">
  </div>
</div>

            </div>
        </div>
    </div>
 </div>
<!-- ========================== Brand Section End =========================== -->
    <!-- ================================== Explore Course Section Start =========================== -->
 <section class="explore-course py-120 bg-main-25 position-relative z-1" >
   <img src="{{ asset('assets/website/images/home/shape2.png') }}" alt="" class="shape six animation-scalation">


    <div class="container">
        <div class="section-heading text-center style-flex gap-24">
            <div class="section-heading__inner text-start">
                <h2 class="mb-0 wow bounceIn">Explore 4,000+ Free Online Courses For Students</h2>
            </div>
            <div class="section-heading__content">
                <p class="section-heading__desc text-start mt-0 text-line-2 wow bounceInUp">At Brain Lift, we provide high-demand digital skills that help students, freelancers, and entrepreneurs succeed. Every course is designed to give you step-by-step learning with real results.</p>
                <a href="{{ route('CourseGrid') }}" class="item-hover__text flex-align gap-8 text-main-600 mt-24 hover-text-decoration-underline transition-1" tabindex="0">
                    See All Course
                    <i class="ph ph-arrow-right"></i>
                </a>
            </div>
        </div>
@php
    use Illuminate\Support\Str;
    use App\Models\CourseCategory;

    // Categories for the pills
    $categories = CourseCategory::orderBy('name')->get(['id','name','slug']);

    // $courses should be passed from the controller with ->with('category')
    // Each $course must have: $course->category (id, name, slug)
@endphp

{{-- =============== Category pills =============== --}}
<div class="nav-tab-wrapper bg-white p-16 mb-40" data-aos="zoom-out">
  <ul class="nav nav-pills common-tab gap-16" id="catTabs" role="tablist">
    {{-- All --}}
    <li class="nav-item" role="presentation">
      <button class="nav-link rounded-pill bg-main-25 text-md fw-medium text-neutral-500 flex-center w-100 gap-8 active"
              type="button" data-cat="all">
        <i class="text-xl d-flex ph-bold ph-squares-four"></i>
        All Categories
      </button>
    </li>
    {{-- Dynamic categories --}}
    @foreach($categories as $c)
      @php $slug = $c->slug ?: Str::slug($c->name); @endphp
      <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill bg-main-25 text-md fw-medium text-neutral-500 flex-center w-100 gap-8"
                type="button" data-cat="{{ $slug }}">
          <i class="text-xl d-flex ph-bold ph-tag"></i>
          {{ $c->name }}
        </button>
      </li>
    @endforeach
  </ul>
</div>

{{-- =============== One grid; we filter cards via JS =============== --}}
<div class="row gy-4" id="courseGrid">
 @forelse ($courses->sortByDesc('id') as $course)
  @php
    $thumb    = $course->thumbnail ? asset('storage/'.$course->thumbnail) : asset('assets/images/thumbs/course-img1.png');
    $mins     = (int)($course->total_duration_minutes ?? 0);
    $duration = $mins ? floor($mins/60).'h '.($mins%60).'m' : '—';
    $lessons  = (int)($course->total_lessons ?? 0);
    $level    = $course->level ?? '—';
    $rating   = number_format((float)($course->rating_avg ?? 0), 1);
    $ratingCount = (int)($course->rating_count ?? 0);

    $isFree   = (bool)($course->is_free ?? false);
    $final    = $course->discount_price ?? $course->price;
    // removed $ sign
    $priceLbl = $isFree ? 'Free' : ($final !== null ? number_format((float)$final, 2) : '—');

    $detailUrl= route('CourseDetail', ['slug' => $course->slug]);

    $cat      = optional($course->category);
    $cardCat  = $cat->slug ?: ($cat->name ? \Illuminate\Support\Str::slug($cat->name) : 'uncategorized');
  @endphp

  <div class="col-lg-4 col-sm-6 course-card" data-cat="{{ $cardCat }}">
    <div class="course-item bg-main-25 rounded-16 p-12 h-100 border border-neutral-30">
      <div class="course-item__thumb rounded-12 overflow-hidden position-relative">
        <a href="{{ $detailUrl }}" class="w-100 h-100">
          <img src="{{ $thumb }}" alt="{{ $course->title }}" class="course-item__img rounded-12 cover-img transition-2">
        </a>

        <div class="flex-align gap-8 bg-main-600 rounded-pill px-24 py-12 text-white position-absolute inset-block-start-0 inset-inline-start-0 mt-20 ms-20 z-1">
          <span class="text-2xl d-flex"><i class="ph ph-clock"></i></span>
          <span class="text-lg fw-medium">{{ $duration }}</span>
        </div>

        <button type="button" class="wishlist-btn w-48 h-48 bg-white text-main-two-600 flex-center position-absolute inset-block-start-0 inset-inline-end-0 mt-20 me-20 z-1 text-2xl rounded-circle transition-2">
          <i class="ph ph-heart"></i>
        </button>
      </div>

      <div class="course-item__content">
        <div>
          <h4 class="mb-28">
            <a href="{{ $detailUrl }}" class="link text-line-2">{{ $course->title }}</a>
          </h4>

          <div class="flex-between gap-8 flex-wrap mb-16">
            <div class="flex-align gap-8">
              <span class="text-neutral-700 text-2xl d-flex"><i class="ph-bold ph-video-camera"></i></span>
              <span class="text-neutral-700 text-lg fw-medium">{{ $lessons }} Lessons</span>
            </div>
            <div class="flex-align gap-8">
              <span class="text-neutral-700 text-2xl d-flex"><i class="ph-bold ph-chart-bar"></i></span>
              <span class="text-neutral-700 text-lg fw-medium">{{ $level }}</span>
            </div>
          </div>

          <div class="flex-between gap-8 flex-wrap">
            <div class="flex-align gap-4">
              <span class="text-2xl fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
              <span class="text-lg text-neutral-700">
                {{ $rating }} <span class="text-neutral-100">({{ number_format($ratingCount) }})</span>
              </span>
            </div>

            <div class="flex-align gap-8">
              <span class="text-neutral-700 text-2xl d-flex"><i class="ph ph-tag"></i></span>
              <span class="text-neutral-700 text-lg fw-medium">
                {{ $cat->name ?? 'Uncategorized' }}
              </span>
            </div>
          </div>
        </div>

        <div class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
          <h4 class="mb-0 text-main-two-600">{{ $priceLbl }}</h4>
          <a href="{{ route('enroll.start', $course->id) }}"
             class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold">
            Enroll Now <i class="ph ph-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
@empty
  <div class="col-12"><div class="text-center text-muted py-5">No courses found.</div></div>
@endforelse

</div>

{{-- message when filter hides all cards --}}
<div id="noCoursesMsg" class="text-center text-muted py-5 d-none">No courses in this category.</div>

{{-- =============== Tiny JS filter =============== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  const tabs   = document.querySelectorAll('#catTabs [data-cat]');
  const cards  = document.querySelectorAll('#courseGrid .course-card');
  const empty  = document.getElementById('noCoursesMsg');

  function applyFilter(cat) {
    let visible = 0;
    cards.forEach(card => {
      const match = (cat === 'all') || (card.dataset.cat === cat);
      card.classList.toggle('d-none', !match);
      if (match) visible++;
    });
    empty.classList.toggle('d-none', visible !== 0);
  }

  tabs.forEach(btn => {
    btn.addEventListener('click', function () {
      tabs.forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      applyFilter(this.dataset.cat);
    });
  });

  // Optional: preselect from ?category=slug
  const q = new URLSearchParams(location.search).get('category');
  const preset = q && document.querySelector('#catTabs [data-cat="'+CSS.escape(q)+'"]');
  preset ? preset.click() : applyFilter('all');
});
</script>



    
   
    
</div>
            </div>
           
            
           
           
         
        </div>
    </div>
 </section>
  

<!-- ================================== Explore Course Section End =========================== -->
    <!-- ============================= Features Section Start ============================== -->
 <section class="features py-120 position-relative overflow-hidden">
    <img src="{{ asset('assets/website/images/home/shape2.png') }}" alt="" class="shape two animation-scalation">
<img src="{{ asset('assets/website/images/home/shape4.png') }}" alt="" class="shape six animation-walking">


    <div class="container">
        <div class="section-heading text-center">
            <h2 class="mb-24 wow bounceIn">Explore 4,000+ Free Online Courses For Students</h2>
            <p class="wow bounceInUp">At Brain Lift, we focus on skills that truly matter. Our courses are designed to help you grow in digital fields and start earning faster. Learn step by step from industry experts</p>
        </div>
        <div class="features-slider">
            <div class="px-8" data-aos="zoom-in" data-aos-duration="400">
                <div class="features-item item-hover animation-item bg-main-25 border border-neutral-30 rounded-16 transition-1 hover-bg-main-600 hover-border-main-600">
                    <span class="mb-32 w-110 h-110 flex-center bg-white rounded-circle">
                      <img src="{{ asset('assets/website/images/home/feature-icon1.png') }}" class="animate__bounce" alt="">

                    </span>
                    <h4 class="mb-16 transition-1 item-hover__text">Meta Ads Mastery</h4>
                    <p class="transition-1 item-hover__text text-line-2">earn Facebook & Instagram advertising, run profitable campaigns, and grow businesses online.</p>
                    <a href="{{ route('CourseGrid') }}" class="item-hover__text flex-align gap-8 text-main-600 mt-24 hover-text-decoration-underline transition-1">
                        View Category
                        <i class="ph ph-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="px-8" data-aos="zoom-in" data-aos-duration="800">
                <div class="features-item item-hover animation-item bg-main-25 border border-neutral-30 rounded-16 transition-1 hover-bg-main-600 hover-border-main-600">
                    <span class="mb-32 w-110 h-110 flex-center bg-white rounded-circle">
                       <img src="{{ asset('assets/website/images/home/feature-icon2.png') }}" class="animate__bounce" alt="">

                    </span>
                    <h4 class="mb-16 transition-1 item-hover__text">Shopify Store Creation</h4>
                    <p class="transition-1 item-hover__text text-line-2">Build, design, and manage a complete Shopify store. Perfect for e-commerce beginners and entrepreneurs.</p>
                    <a href="{{ route('CourseGrid') }}" class="item-hover__text flex-align gap-8 text-main-600 mt-24 hover-text-decoration-underline transition-1">
                        View Category
                        <i class="ph ph-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="px-8" data-aos="zoom-in" data-aos-duration="1200">
                <div class="features-item item-hover animation-item bg-main-25 border border-neutral-30 rounded-16 transition-1 hover-bg-main-600 hover-border-main-600">
                    <span class="mb-32 w-110 h-110 flex-center bg-white rounded-circle">
<img src="{{ asset('assets/website/images/home/feature-icon3.png') }}" class="animate__bounce" alt="">
                    </span>
                    <h4 class="mb-16 transition-1 item-hover__text">Artificial Intelligence (AI) Skills</h4>
                    <p class="transition-1 item-hover__text text-line-2">Discover how AI tools can save time, boost productivity, and create smarter workflows.</p>
                    <a href="{{ route('CourseGrid') }}" class="item-hover__text flex-align gap-8 text-main-600 mt-24 hover-text-decoration-underline transition-1">
                        View Category
                        <i class="ph ph-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="px-8" data-aos="zoom-in" data-aos-duration="1600">
                <div class="features-item item-hover animation-item bg-main-25 border border-neutral-30 rounded-16 transition-1 hover-bg-main-600 hover-border-main-600">
                    <span class="mb-32 w-110 h-110 flex-center bg-white rounded-circle">
                       <img src="{{ asset('assets/website/images/home/feature-icon2.png') }}" class="animate__bounce" alt="">

                    </span>
                    <h4 class="mb-16 transition-1 item-hover__text">Amazon Selling Guide</h4>
                    <p class="transition-1 item-hover__text text-line-2">Step into the world of Amazon FBA & dropshipping. Learn product hunting, listing, and scaling your store.</p>
                    <a href="{{ route('CourseGrid') }}" class="item-hover__text flex-align gap-8 text-main-600 mt-24 hover-text-decoration-underline transition-1">
                        View Category
                        <i class="ph ph-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex-align gap-16 mt-40 justify-content-center">
            <button type="button" id="features-prev" class="slick-prev slick-arrow flex-center rounded-circle border border-gray-100 hover-border-main-600 text-xl hover-bg-main-600 hover-text-white transition-1 w-48 h-48">
                <i class="ph ph-caret-left"></i>
            </button>
            <button type="button" id="features-next" class="slick-next slick-arrow flex-center rounded-circle border border-gray-100 hover-border-main-600 text-xl hover-bg-main-600 hover-text-white transition-1 w-48 h-48">
                <i class="ph ph-caret-right"></i>
            </button>
        </div>
    </div>
 </section>
<!-- ============================= Features Section End ============================== -->


    
    <!-- ================================ About Section Start ==================================== -->
 <section class="about py-120 position-relative z-1 mash-bg-main mash-bg-main-two">
  <img src="{{ asset('assets/website/images/home/shape2.png') }}" alt="" class="shape one animation-scalation">
<img src="{{ asset('assets/website/images/home/shape6.png') }}" alt="" class="shape four animation-scalation">


    <div class="position-relative">
        <div class="container">
            <div class="row gy-xl-0 gy-5 flex-wrap-reverse align-items-center">
                <div class="col-xl-6">
                    <div class="about-thumbs position-relative pe-lg-5">
                       <img src="{{ asset('assets/website/images/home/shape7.png') }}" alt="" class="shape seven animation-scalation">

    
                        <div class="offer-message px-24 py-12 rounded-12 bg-main-two-50 fw-medium flex-align d-inline-flex gap-16 border border-neutral-30 animation-upDown">
                            <span class="flex-shrink-0 w-48 h-48 bg-main-two-600 text-white text-2xl flex-center rounded-circle"><i class="ph ph-watch"></i></span>
                            <div>
                                <h6 class="mb-4">20% OFF</h6>
                                <span class="text-neutral-500">For All Courses</span>
                            </div>
                        </div>
                        <div class="row gy-4">
                            <div class="col-sm-6">
                               <img src="{{ asset('assets/website/images/home/about-img1.png') }}" 
     alt="" class="rounded-12 w-100"
     data-tilt data-tilt-max="15" data-tilt-speed="500"
     data-tilt-perspective="5000" data-tilt-full-page-listening>

                            </div>
                            <div class="col-sm-6">
                                <div class="flex-align gap-24 mb-24">
                                    <div class="bg-main-600 rounded-12 text-center py-24 px-2 w-50-percent" data-aos="fade-right">    
                                        <h1 class="mb-0 text-white counter">16+</h1>
                                        <span class="text-white">Years of experience</span>
                                    </div>
                                    <div class="bg-neutral-700 rounded-12 text-center py-24 px-2 w-50-percent" data-aos="fade-left">    
                                        <h1 class="mb-0 text-white counter">3.2k</h1>
                                        <span class="text-white">Years of experience</span>
                                    </div>
                                </div>
                              <img src="{{ asset('assets/website/images/home/about-img2.png') }}" 
     alt="" class="rounded-12 w-100"
     data-tilt data-tilt-max="15" data-tilt-speed="500"
     data-tilt-perspective="5000" data-tilt-full-page-listening>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="about-content">
                        <div class="mb-40">
                            <div class="flex-align gap-8 mb-16 wow bounceInDown">
                                <span class="w-8 h-8 bg-main-600 rounded-circle"></span>
                                <h5 class="text-main-600 mb-0 ">About EduAll</h5>
                            </div>
                            <h2 class="mb-24 wow bounceIn">The Place Where You Can Achieve</h2>
                            <p class="text-neutral-500 text-line-2 wow bounceInUp">Brain Lift, where learning creates opportunities. Whether you are a student, freelancer, or professional, our goal is to help you gain practical skills and achieve real success.</p>
                        </div>
    
                        <div class="flex-align align-items-start gap-28 mb-32" data-aos="fade-left" data-aos-duration="200">
                            <span class="w-80 h-80 bg-main-25 border border-neutral-30 flex-center rounded-circle flex-shrink-0">
                               <img src="{{ asset('assets/website/images/home/about-img1.png') }}" 
     alt="" >

                            </span>
                            <div class="flex-grow-1">
                                <h4 class="text-neutral-500 mb-12">Our Mission</h4>
                                <p class="text-neutral-500">Driven by a team of passionate trainers and industry experts, Brain Lift is dedicated to providing step-by-step digital skills training that leads to growth, confidence, and financial independence.</p>
                            </div>
                        </div>
                        <div class="flex-align align-items-start gap-28 mb-0" data-aos="fade-left" data-aos-duration="400">
                            <span class="w-80 h-80 bg-main-25 border border-neutral-30 flex-center rounded-circle flex-shrink-0">
                                <img src="{{ asset('assets/website/images/home/about-img2.png') }}" 
     alt="" >

                            </span>
                            <div class="flex-grow-1">
                                <h4 class="text-neutral-500 mb-12">Our Vision</h4>
                                <p class="text-neutral-500">We envision a world where every learner can upskill, earn, and grow through practical digital education. At Brain Lift, we guide you at every step so you can explore new horizons with confidence.</p>
                            </div>
                        </div>
    
                        <div class="flex-align flex-wrap gap-32 pt-40 border-top border-neutral-50 mt-40 border-dashed border-0" data-aos="fade-left" data-aos-duration="600">
                            <a href="{{ route('CourseGrid') }}" class="btn btn-main rounded-pill flex-align gap-8">
                                Read More
                                <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                            </a>
                            <div class="flex-align gap-20">
                                <img src="{{ asset('assets/website/images/home/ceo-img.png') }}"
     alt="" class="w-52 h-52 rounded-circle object-fit-cover flex-shrink-0">

<div class="flex-grow-1">
    <span class="mb-4">
        <img src="{{ asset('assets/website/images/home/thumbs/signature.png') }}" alt="">
    </span>
    <span class="text-sm d-block">Ch. M. Usham Ilyas</span>
    <span class="text-sm d-block">CEO of Brain Lift</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </section>
<!-- ================================ About Section End ==================================== -->
    
    

    <!-- =========================== CHoose Us Section Start ================================ -->
<section class="choose-us pt-120 position-relative z-1 mash-bg-main mash-bg-main-two">
<img src="{{ asset('assets/website/images/home/shapes/shape2.png') }}"
     alt="" class="shape one animation-scalation">

<img src="{{ asset('assets/website/images/home/shapes/shape2.png') }}"
     alt="" class="shape six animation-scalation">


<div class="container">
    <div class="row gy-4">
        <div class="col-xl-6">
            <div class="choose-us__content">
                <div class="mb-40">
                    <div class="flex-align gap-8 mb-16 wow bounceInDown">
                        <span class="w-8 h-8 bg-main-600 rounded-circle"></span>
                        <h5 class="text-main-600 mb-0">Why Choose Us</h5>
                    </div>
                    <h2 class="mb-24  wow bounceIn">Our Commitment to Excellence, Learn, Grow & Success.</h2>
                    <p class="text-neutral-500 text-line-2  wow bounceInUp">We are passionate about transforming lives through education. Founded with a vision to make learning accessible to all, we believe in the power of knowledge to unlock opportunities and shape the future.</p>
                </div>

                <ul>
                    <li class="flex-align gap-12 mb-16" data-aos="fade-up-left"  data-aos-duration="200">
                        <span class="flex-shrink-0 text-xl text-main-600 d-flex"><i class="ph-bold ph-checks"></i></span>
                        <span class="flex-grow-1 text-neutral-500">9/10 Average Satisfaction Rate</span>
                    </li>
                    <li class="flex-align gap-12 mb-16" data-aos="fade-up-left"  data-aos-duration="400">
                        <span class="flex-shrink-0 text-xl text-main-600 d-flex"><i class="ph-bold ph-checks"></i></span>
                        <span class="flex-grow-1 text-neutral-500">96% Completitation Rate</span>
                    </li>
                    <li class="flex-align gap-12 mb-16" data-aos="fade-up-left"  data-aos-duration="500">
                        <span class="flex-shrink-0 text-xl text-main-600 d-flex"><i class="ph-bold ph-checks"></i></span>
                        <span class="flex-grow-1 text-neutral-500">Friendly Environment & Expert Teacher</span>
                    </li>
                </ul>

                <div class="pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
                    <a href="about.html" class="btn btn-main rounded-pill flex-align d-inline-flex gap-8">
                        Read More
                        <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                    </a>
                </div>

            </div>
        </div>
        <div class="col-xl-6">
            <div class="choose-us__thumbs position-relative">

                <div class="offer-message style-two px-24 py-12 rounded-12 bg-white fw-medium flex-align d-inline-flex gap-16 box-shadow-lg animation-upDown">
                    <span class="flex-shrink-0 w-48 h-48 bg-dark-yellow text-white text-2xl flex-center rounded-circle">
                       <img src="{{ asset('assets/website/images/home/stars.png') }}" alt="Stars">

                    </span>
                    <div>
                        <span class="text-lg text-neutral-700 d-block">
                            4.6
                            <span class="text-neutral-100">(2.4k)</span>
                        </span>
                        <span class="text-neutral-500">AVG Reviews</span>
                    </div>
                </div>

                <div class="banner-box one style-two px-24 py-12 rounded-12 bg-white fw-medium box-shadow-lg d-inline-block" data-aos="fade-left">
                    <span class="text-main-600">36k+</span> Enrolled Students
                  <div class="enrolled-students mt-12">
  <img src="{{ asset('assets/website/images/home/enroll-student-img1.png') }}" alt="Student 1" class="w-48 h-48 rounded-circle object-fit-cover">
  <img src="{{ asset('assets/website/images/home/enroll-student-img2.png') }}" alt="Student 2" class="w-48 h-48 rounded-circle object-fit-cover">
  <img src="{{ asset('assets/website/images/home/enroll-student-img3.png') }}" alt="Student 3" class="w-48 h-48 rounded-circle object-fit-cover">
  <img src="{{ asset('assets/website/images/home/enroll-student-img4.png') }}" alt="Student 4" class="w-48 h-48 rounded-circle object-fit-cover">
  <img src="{{ asset('assets/website/images/home/enroll-student-img5.png') }}" alt="Student 5" class="w-48 h-48 rounded-circle object-fit-cover">
  <img src="{{ asset('assets/website/images/home/enroll-student-img6.png') }}" alt="Student 6" class="w-48 h-48 rounded-circle object-fit-cover">
</div>

                </div>

                <div class="text-end" data-aos="zoom-out">
                   <div class="d-sm-inline-block d-block position-relative">
  <img src="{{ asset('assets/website/images/home/choose-us-img1.png') }}"
       alt=""
       class="choose-us__img rounded-12"
       data-tilt data-tilt-max="16" data-tilt-speed="500"
       data-tilt-perspective="5000" data-tilt-full-page-listening>
  <span class="shadow-main-two w-80 h-80 flex-center bg-main-two-600 rounded-circle position-absolute inset-block-start-0 inset-inline-start-0 mt-40 ms--40 animation-upDown">
    <img src="{{ asset('assets/website/images/home/book.png') }}" alt="">
  </span>
</div>

<div class="animation-video" data-aos="zoom-in">
  <img src="{{ asset('assets/website/images/home/choose-us-img2.png') }}"
       alt=""
       class="border border-white rounded-circle border-3"
       data-tilt>
  <a href="https://www.youtube.com/watch?v=MFLVmAE4cqg"
     class="play-button w-48 h-48 flex-center bg-main-600 text-white rounded-circle text-xl position-absolute top-50 start-50 translate-middle">
    <i class="ph-fill ph-play"></i>
  </a>
</div>

            </div>
        </div>
    </div>
</div>
</section>
<!-- =========================== CHoose Us Section End ================================ -->

    <!-- ========================== Counter Section start ============================== -->
 <section class="counter py-120">
    <div class="container">
        <div class="row gy-4">
            <div class="col-xl-3 col-sm-6 col-xs-6" data-aos="fade-up" data-aos-duration="200" >
                <div class="counter-item animation-item h-100 text-center px-16 py-32 rounded-12 bg-main-25 border border-neutral-30">
                    <span class="w-80 h-80 flex-center d-inline-flex bg-white text-main-600 text-40 rounded-circle box-shadow-md mb-24">
                        <i class="animate__wobble ph ph-users"></i>
                    </span>
                    <h2 class="display-four mb-16 text-neutral-700 counter">1.6K</h2>
                    <span class="text-neutral-500 text-lg">Successfully Trained</span>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-xs-6" data-aos="fade-up" data-aos-duration="400" >
                <div class="counter-item animation-item h-100 text-center px-16 py-32 rounded-12 bg-main-two-25 border border-neutral-30">
                    <span class="w-80 h-80 flex-center d-inline-flex bg-white text-main-two-600 text-40 rounded-circle box-shadow-md mb-24">
                        <i class="animate__wobble ph ph-video-camera"></i>
                    </span>
                    <h2 class="display-four mb-16 text-neutral-700 counter"> 16.5K</h2>
                    <span class="text-neutral-500 text-lg">Courses Completed</span>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-xs-6" data-aos="fade-up" data-aos-duration="600" >
                <div class="counter-item animation-item h-100 text-center px-16 py-32 rounded-12 bg-main-25 border border-neutral-30">
                    <span class="w-80 h-80 flex-center d-inline-flex bg-white text-main-600 text-40 rounded-circle box-shadow-md mb-24">
                        <i class="animate__wobble ph ph-thumbs-up"></i>
                    </span>
                    <h2 class="display-four mb-16 text-neutral-700 counter">45.8K</h2>
                    <span class="text-neutral-500 text-lg">Satisfaction Rate</span>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-xs-6" data-aos="fade-up" data-aos-duration="800" >
                <div class="counter-item animation-item h-100 text-center px-16 py-32 rounded-12 bg-main-two-25 border border-neutral-30">
                    <span class="w-80 h-80 flex-center d-inline-flex bg-white text-main-two-600 text-40 rounded-circle box-shadow-md mb-24">
                        <i class="animate__wobble ph ph-users-three"></i>
                    </span>
                    <h2 class="display-four mb-16 text-neutral-700 counter">55.6K</h2>
                    <span class="text-neutral-500 text-lg">Students Community</span>
                </div>
            </div>
        </div>
    </div>
 </section>
<!-- ========================== Counter Section End ============================== -->

    <!-- ================================= testimonials Section Start ========================================= -->
 <section class="testimonials py-120 position-relative z-1 bg-main-25">
   <img src="{{ asset('assets/website/images/home/shape2.png') }}" alt="" class="shape six animation-scalation">
<img src="{{ asset('assets/website/images/home/shape3.png') }}" alt="" class="shape four animation-rotation">

    <div class="container">
        <div class="row gy-5">
            <div class="col-lg-6">
                <div class="testimonials__thumbs-slider pe-lg-5 me-xxl-5">
                    <div class="testimonials__thumbs wow bounceIn" data-tilt data-tilt-max="15" data-tilt-speed="500" data-tilt-perspective="5000" data-tilt-full-page-listening>
                      <img src="{{ asset('assets/website/images/home/testimonial-img1.png') }}" alt="">

                    </div>
                    <div class="testimonials__thumbs wow bounceIn" data-tilt data-tilt-max="15" data-tilt-speed="500" data-tilt-perspective="5000" data-tilt-full-page-listening>
                        <img src="{{ asset('assets/website/images/home/testimonial-img2.png') }}" alt="">

                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="testimonials__content">
                    <div class="section-heading style-left">
                        <div class="flex-align gap-8 mb-16 wow bounceInDown">
                            <span class="w-8 h-8 bg-main-600 rounded-circle"></span>
                            <h5 class="text-main-600 mb-0">What Our Students Say</h5>
                        </div>
                        <h2 class="mb-24 wow bounceIn">Testimonials from Happy Learners for EduAll</h2>
                        <p class="text-neutral-500 text-line-2 wow bounceInUp">16+ million Students are already learning on EduAll Platform</p>
                    </div>

                    <div class="testimonials__slider">
                        <div class="testimonials-item">
                            <ul class="flex-align gap-8 mb-16" data-aos="fade-left" data-aos-duration="800">
                                <li class="text-warning-600 text-xl d-flex"><i class="ph-fill ph-star"></i></li>
                                <li class="text-warning-600 text-xl d-flex"><i class="ph-fill ph-star"></i></li>
                                <li class="text-warning-600 text-xl d-flex"><i class="ph-fill ph-star"></i></li>
                                <li class="text-warning-600 text-xl d-flex"><i class="ph-fill ph-star"></i></li>
                                <li class="text-warning-600 text-xl d-flex"><i class="ph-fill ph-star-half"></i></li>
                            </ul>
                            <p class="text-neutral-700" data-aos="fade-left" data-aos-duration="1200">"Enrolling in courses at EduAll was one of the best decisions I've made for my career. The flexibility of the online learning platform allowed me to study at my own pace while balancing my work”</p>
                            <h4 class="mt-48 mb-8" data-aos="fade-left">Kathryn Murphy</h4>
                            <span class="text-neutral-700" data-aos="fade-left">Software Developer</span>
                        </div>
                        <div class="testimonials-item">
                            <ul class="flex-align gap-8 mb-16" data-aos="fade-left" data-aos-duration="800">
                                <li class="text-warning-600 text-xl d-flex"><i class="ph-fill ph-star"></i></li>
                                <li class="text-warning-600 text-xl d-flex"><i class="ph-fill ph-star"></i></li>
                                <li class="text-warning-600 text-xl d-flex"><i class="ph-fill ph-star"></i></li>
                                <li class="text-warning-600 text-xl d-flex"><i class="ph-fill ph-star"></i></li>
                                <li class="text-warning-600 text-xl d-flex"><i class="ph-fill ph-star-half"></i></li>
                            </ul>
                            <p class="text-neutral-700" data-aos="fade-left" data-aos-duration="1200">"Signing up for courses at EduAll was quite possibly of the best choice I've made for my vocation. The adaptability of the internet learning stage permitted me to learn at my own speed while adjusting my work"</p>
                            <h4 class="mt-48 mb-8" data-aos="fade-left">John Doe</h4>
                            <span class="text-neutral-700" data-aos="fade-left">UX/UI Designer</span>
                        </div>
                    </div>
                    <div class="flex-align gap-16 mt-40">
                        <button type="button" id="testimonials-prev" class="slick-prev slick-arrow flex-center rounded-circle border border-gray-100 hover-border-main-600 text-xl hover-bg-main-600 hover-text-white transition-1 w-48 h-48">
                            <i class="ph ph-caret-left"></i>
                        </button>
                        <button type="button" id="testimonials-next" class="slick-next slick-arrow flex-center rounded-circle border border-gray-100 hover-border-main-600 text-xl hover-bg-main-600 hover-text-white transition-1 w-48 h-48">
                            <i class="ph ph-caret-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </section>
<!-- ================================= testimonials Section End ========================================= -->
    
    <!-- ================================= Blog Section Start ========================================= -->
{{-- <section class="blog py-120 mash-bg-main mash-bg-main-two position-relative">
<img src="{{ asset('assets/website/images/home/shape2.png') }}" alt="" class="shape six animation-scalation">
<img src="{{ asset('assets/website/images/home/shape6.png') }}" alt="" class="shape four animation-rotation">


    <div class="container">
        <div class="section-heading text-center">
            <h2 class="mb-24 wow bounceIn">Recent Articles</h2>
            <p class="wow bounceInUp">Consectetur adipisicing elit, sed do eiusmod tempor inc idid unt ut labore et dolore magna aliqua enim ad...</p>
        </div>
        <div class="row gy-4">
            <div class="col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="200" >
                <div class="blog-item scale-hover-item bg-main-25 rounded-16 p-12 h-100 border border-neutral-30">
                    <div class="rounded-12 overflow-hidden position-relative">
                        <a href="#" class="w-100 h-100">
                            <img src="{{ asset('assets/website/images/home/blog-img1.png') }}" alt="Blog Image" class="scale-hover-item__img rounded-12 cover-img transition-2">

                        </a>
                    </div>
                    <div class="p-24 pt-32">
                        <div class="">
                            <span class="px-20 py-8 bg-main-two-600 rounded-8 text-white fw-medium mb-20">Student life</span>
                            <h4 class="mb-28">
                                <a href="#" class="link text-line-2">The Importance of Diversity in Higher Education</a>
                            </h4>
                            <div class="flex-align gap-14 flex-wrap my-20">
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-user-circle"></i></span>
                                    <span class="text-neutral-500 text-lg">Jeswal</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-calendar-dot"></i></span>
                                    <span class="text-neutral-500 text-lg">12 May, 24</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-chat-dots"></i></span>
                                    <span class="text-neutral-500 text-lg">24</span>
                                </div>
                            </div>
                            <p class="text-neutral-500 text-line-2">Unlock the secrets to effective time management in the digital learning space...</p>
                        </div>
                        <div class="pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
                            <a href="#" class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold" tabindex="0">
                                Read More
                                <i class="ph ph-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="400" >
                <div class="blog-item scale-hover-item bg-main-25 rounded-16 p-12 h-100 border border-neutral-30">
                    <div class="rounded-12 overflow-hidden position-relative">
                        <a href="#" class="w-100 h-100">
                           <img src="{{ asset('assets/website/images/home/blog-img2.png') }}" alt="Blog Image" class="scale-hover-item__img rounded-12 cover-img transition-2">

                        </a>
                    </div>
                    <div class="p-24 pt-32">
                        <div class="">
                            <span class="px-20 py-8 bg-success-600 rounded-8 text-white fw-medium mb-20">Freedom</span>
                            <h4 class="mb-28">
                                <a href="#" class="link text-line-2">The Importance of Diversity in Higher Education</a>
                            </h4>
                            <div class="flex-align gap-14 flex-wrap my-20">
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-user-circle"></i></span>
                                    <span class="text-neutral-500 text-lg">Jeswal</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-calendar-dot"></i></span>
                                    <span class="text-neutral-500 text-lg">12 May, 24</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-chat-dots"></i></span>
                                    <span class="text-neutral-500 text-lg">24</span>
                                </div>
                            </div>
                            <p class="text-neutral-500 text-line-2">Unlock the secrets to effective time management in the digital learning space...</p>
                        </div>
                        <div class="pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
                            <a href="#" class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold" tabindex="0">
                                Read More
                                <i class="ph ph-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="600" >
                <div class="blog-item scale-hover-item bg-main-25 rounded-16 p-12 h-100 border border-neutral-30">
                    <div class="rounded-12 overflow-hidden position-relative">
                        <a href="#" class="w-100 h-100">
                           <img src="{{ asset('assets/website/images/home/blog-img3.png') }}" alt="Blog Image" class="scale-hover-item__img rounded-12 cover-img transition-2">

                        </a>
                    </div>
                    <div class="p-24 pt-32">
                        <div class="">
                            <span class="px-20 py-8 bg-main-two-600 rounded-8 text-white fw-medium mb-20">Online</span>
                            <h4 class="mb-28">
                                <a href="#" class="link text-line-2">The Importance of Diversity in Higher Education</a>
                            </h4>
                            <div class="flex-align gap-14 flex-wrap my-20">
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-user-circle"></i></span>
                                    <span class="text-neutral-500 text-lg">Jeswal</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-calendar-dot"></i></span>
                                    <span class="text-neutral-500 text-lg">12 May, 24</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-chat-dots"></i></span>
                                    <span class="text-neutral-500 text-lg">24</span>
                                </div>
                            </div>
                            <p class="text-neutral-500 text-line-2">Unlock the secrets to effective time management in the digital learning space...</p>
                        </div>
                        <div class="pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
                            <a href="#" class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold" tabindex="0">
                                Read More
                                <i class="ph ph-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> --}}
<!-- ================================= Blog Section End ========================================= -->


    <!-- ================================= Certificate Section Start ================================= -->
<div class="certificate">
    <div class="container container--lg">
        <div class="certificate-box px-16 bg-main-600 rounded-16">
            <div class="container">
                <div class="position-relative py-80">
                    <div class="row align-items-center">
                        <div class="col-xl-6">
                            <div class="certificate__content">
                                <div class="flex-align gap-8 mb-16 wow bounceInDown">
                                    <span class="w-8 h-8 bg-white rounded-circle"></span>
                                    <h5 class="text-white mb-0">Get Certificate</h5>
                                </div>
                                <h2 class="text-white mb-40 fw-medium wow bounceIn">Get Quality Skills Certificate From the LMS Brain Lift</h2>
                                <a href="" class="btn btn-white rounded-pill flex-align d-inline-flex gap-8 hover-bg-main-800 wow bounceInUp">
                                    Get Started Now
                                    <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-6 d-xl-block d-none">
                            <div class="certificate__thumb" data-aos="fade-up-left">    
                               <img src="{{ asset('assets/website/images/home/certificate-img.png') }}" alt="" data-tilt data-tilt-max="8" data-tilt-speed="500" data-tilt-perspective="5000" data-tilt-full-page-listening>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>
<!-- ================================= Certificate Section End ================================= -->
    
    
<!-- ==================== Footer Start Here ==================== -->

<!-- ==================== Footer End Here ==================== -->
  

        <!-- Jquery js -->
    <!-- <script src="assets/js/jquery-3.7.1.min.js"></script> -->
@endsection