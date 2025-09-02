{{-- resources/views/website/pages/enroll-start.blade.php --}}
@extends('website.layouts.main')

@section('content')

<style>
  /* Minimal fallback so tabs work even without Bootstrap JS */
  .tab-pane { display: none; }
  .tab-pane.show.active { display: block; }
  .nav-tabs .nav-link.active { font-weight: 600; }
</style>

@php
  // Currency + Discount config
  $currencySym = env('APP_CURRENCY', 'USD');
  $allCoursesDiscount = (float) config('site.all_courses_discount', 0.20); // 0.20 = 20%
  $allCoursesDiscountPct = (int) round(config('site.all_courses_discount_pct', $allCoursesDiscount * 100));

  // Current course basic pricing
  $coursePrice = (float) ($course->discount_price ?? $course->price ?? 0);

  // Fetch all courses once to compute "all courses" pricing (safe in view)
  try {
      $allCourses = \App\Models\Course::select('id','title','price','discount_price')
          ->when(\Schema::hasColumn('courses','is_active'), fn($q) => $q->where('is_active', 1))
          ->get();
  } catch (\Throwable $e) {
      $allCourses = collect();
  }

  $allSubtotal = (float) $allCourses->sum(function($c) {
      return (float)($c->discount_price ?? $c->price ?? 0);
  });
  $allDiscount = round($allSubtotal * $allCoursesDiscount, 2);
  $allTotal    = max(0, round($allSubtotal - $allDiscount, 2));

  $activeTab = session('tab','register'); // 'register' or 'login'
@endphp

<div class="container py-4">
  @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if (session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <div class="row g-4">
    {{-- Left: Course summary --}}
    <div class="col-lg-5">
      <div class="card shadow-sm">
        <div class="card-header fw-semibold">Course Summary</div>
        <div class="card-body">
          @php
            $thumb = $course->thumbnail ? asset('storage/'.$course->thumbnail) : asset('assets/images/thumbs/course-img1.png');
          @endphp
          <div class="d-flex gap-3 mb-3">
            <img src="{{ $thumb }}" alt="{{ $course->title }}" class="rounded border" style="width:96px;height:96px;object-fit:cover">
            <div>
              <h5 class="mb-1">{{ $course->title }}</h5>
              @if(!empty($course->slug)) <div class="small text-muted">/{{ $course->slug }}</div> @endif
            </div>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between">
              <span>Selected Course Price</span>
              <span class="fw-semibold">{{ $coursePrice>0 ? number_format($coursePrice,2).' '.$currencySym : 'Free' }}</span>
            </li>
          </ul>
          <div class="mt-3">
            <a href="{{ route('CourseDetail', $course->slug) }}" class="btn btn-outline-secondary btn-sm">View details</a>
          </div>
        </div>
      </div>
    </div>

    {{-- Right: Auth + All Courses toggle --}}
    <div class="col-lg-7">
      <div class="card shadow-sm">
        <div class="card-header fw-semibold">Continue to Checkout</div>
        <div class="card-body">

          <ul class="nav nav-tabs" role="tablist" id="enrollTabs">
            <li class="nav-item">
              <button class="nav-link {{ $activeTab==='register'?'active':'' }}"
                      data-bs-toggle="tab" data-bs-target="#tab-register" type="button" role="tab">
                Register
              </button>
            </li>
            <li class="nav-item">
              <button class="nav-link {{ $activeTab==='login'?'active':'' }}"
                      data-bs-toggle="tab" data-bs-target="#tab-login" type="button" role="tab">
                Login
              </button>
            </li>
          </ul>

          <div class="tab-content pt-3" id="enrollTabContent">
            {{-- Register --}}
            <div class="tab-pane fade {{ $activeTab==='register'?'show active':'' }}" id="tab-register" role="tabpanel">
              <form id="registerForm" method="POST" action="{{ route('enroll.register') }}" class="row g-3" novalidate>
                @csrf
                <input type="hidden" name="from_cart" value="{{ request()->boolean('from_cart') ? 1 : 0 }}">
                {{-- This will be disabled when "All Courses" is checked --}}
                <input type="hidden" id="course_id_register" name="course_id" value="{{ $course->id }}">

                {{-- All Courses switch --}}
                <div class="col-12">
                  <div class="form-check form-switch d-flex align-items-center gap-2 mb-2">
                    <input class="form-check-input" type="checkbox" id="all_courses_register" name="all_courses" value="1" {{ old('all_courses') ? 'checked' : '' }}>
                    <label class="form-check-label" for="all_courses_register">
                      Enroll in <strong>All Courses</strong> (save {{ $allCoursesDiscountPct }}%)
                    </label>
                  </div>
                </div>

                {{-- Summary box (auto toggled by JS) --}}
                <div class="col-12">
                  <div id="summaryRegisterSingle" class="alert alert-light border">
                    <div class="d-flex justify-content-between"><span>Subtotal</span><strong>{{ number_format($coursePrice,2) }} {{ $currencySym }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Discount</span><strong>0.00 {{ $currencySym }}</strong></div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between"><span>Total</span><strong>{{ number_format($coursePrice,2) }} {{ $currencySym }}</strong></div>
                  </div>

                  <div id="summaryRegisterAll" class="alert alert-success border d-none">
                    <div class="d-flex justify-content-between"><span>All Courses Subtotal</span><strong>{{ number_format($allSubtotal,2) }} {{ $currencySym }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Discount ({{ $allCoursesDiscountPct }}%)</span><strong>-{{ number_format($allDiscount,2) }} {{ $currencySym }}</strong></div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between"><span>Total</span><strong>{{ number_format($allTotal,2) }} {{ $currencySym }}</strong></div>
                  </div>
                </div>

                {{-- User fields --}}
                <div class="col-md-6">
                  <label class="form-label">First name</label>
                  <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Last name</label>
                  <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" required>
                </div>
                <div class="col-12">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Confirm Password</label>
                  <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <div class="col-12 pt-2">
                  <button type="submit" class="btn btn-primary btn-lg">Create account & Continue</button>
                </div>
              </form>
            </div>

            {{-- Login --}}
            <div class="tab-pane fade {{ $activeTab==='login'?'show active':'' }}" id="tab-login" role="tabpanel">
              <form id="loginForm" method="POST" action="{{ route('enroll.login') }}" class="row g-3" novalidate>
                @csrf
                <input type="hidden" name="from_cart" value="{{ request()->boolean('from_cart') ? 1 : 0 }}">
                {{-- This will be disabled when "All Courses" is checked --}}
                <input type="hidden" id="course_id_login" name="course_id" value="{{ $course->id }}">

                {{-- All Courses switch --}}
                <div class="col-12">
                  <div class="form-check form-switch d-flex align-items-center gap-2 mb-2">
                    <input class="form-check-input" type="checkbox" id="all_courses_login" name="all_courses" value="1">
                    <label class="form-check-label" for="all_courses_login">
                      Enroll in <strong>All Courses</strong> (save {{ $allCoursesDiscountPct }}%)
                    </label>
                  </div>
                </div>

                {{-- Summary box (auto toggled by JS) --}}
                <div class="col-12">
                  <div id="summaryLoginSingle" class="alert alert-light border">
                    <div class="d-flex justify-content-between"><span>Subtotal</span><strong>{{ number_format($coursePrice,2) }} {{ $currencySym }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Discount</span><strong>0.00 {{ $currencySym }}</strong></div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between"><span>Total</span><strong>{{ number_format($coursePrice,2) }} {{ $currencySym }}</strong></div>
                  </div>

                  <div id="summaryLoginAll" class="alert alert-success border d-none">
                    <div class="d-flex justify-content-between"><span>All Courses Subtotal</span><strong>{{ number_format($allSubtotal,2) }} {{ $currencySym }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Discount ({{ $allCoursesDiscountPct }}%)</span><strong>-{{ number_format($allDiscount,2) }} {{ $currencySym }}</strong></div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between"><span>Total</span><strong>{{ number_format($allTotal,2) }} {{ $currencySym }}</strong></div>
                  </div>
                </div>

                <div class="col-12">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>
                <div class="col-12">
                  <label class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-12 pt-2">
                  <button type="submit" id="loginSubmit" form="loginForm" class="btn btn-success btn-lg">
                    Login & Go To Checkout
                  </button>
                </div>
              </form>
            </div>
          </div> <!-- /.tab-content -->

        </div>
      </div>
    </div>
  </div>
</div>

{{-- Tabs polyfill + "All Courses" toggle + bullet-proof login submit --}}
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // ===== Tabs Polyfill (works even without Bootstrap JS) =====
    (function () {
      const tabButtons = Array.from(document.querySelectorAll('[data-bs-toggle="tab"]'));
      if (!tabButtons.length) return;

      function activate(targetSelector, clickedBtn) {
        const container = document.getElementById('enrollTabContent');
        const allPanes = Array.from(container.querySelectorAll('.tab-pane'));
        const allBtns  = tabButtons;

        // Deactivate all
        allBtns.forEach(b => { b.classList.remove('active'); b.setAttribute('aria-selected','false'); });
        allPanes.forEach(p => { p.classList.remove('show','active'); });

        // Activate chosen
        clickedBtn.classList.add('active');
        clickedBtn.setAttribute('aria-selected','true');
        const pane = document.querySelector(targetSelector);
        if (pane) pane.classList.add('show','active');
      }

      // Bind clicks
      tabButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
          e.preventDefault();
          const target = btn.getAttribute('data-bs-target') || btn.getAttribute('href');
          if (!target) return;
          activate(target, btn);
          if (history.replaceState) history.replaceState(null, '', target);
          else location.hash = target;
        });
      });

      // Open tab by hash if present
      const hash = location.hash;
      if (hash === '#tab-login' || hash === '#tab-register') {
        const btn = document.querySelector('[data-bs-target="' + hash + '"]');
        if (btn) btn.click();
      }
    })();

    // ===== All Courses toggle handling =====
    const regAll = document.getElementById('all_courses_register');
    const logAll = document.getElementById('all_courses_login');

    const regCourseId = document.getElementById('course_id_register');
    const logCourseId = document.getElementById('course_id_login');

    const regSingle = document.getElementById('summaryRegisterSingle');
    const regAllBox = document.getElementById('summaryRegisterAll');
    const logSingle = document.getElementById('summaryLoginSingle');
    const logAllBox = document.getElementById('summaryLoginAll');

    function applyAllState(src, twin, hiddenInput, singleBox, allBox) {
      const checked = !!src?.checked;
      // Disable course_id when "all" is chosen so backend can use required_without
      if (hiddenInput) {
        if (checked) hiddenInput.setAttribute('disabled', 'disabled');
        else hiddenInput.removeAttribute('disabled');
      }
      // Toggle summaries
      if (singleBox && allBox) {
        if (checked) { singleBox.classList.add('d-none'); allBox.classList.remove('d-none'); }
        else { allBox.classList.add('d-none'); singleBox.classList.remove('d-none'); }
      }
      // Mirror to the other tab
      if (twin) twin.checked = checked;
    }

    // Bind events
    regAll?.addEventListener('change', () => applyAllState(regAll, logAll, regCourseId, regSingle, regAllBox));
    logAll?.addEventListener('change', () => applyAllState(logAll, regAll, logCourseId, logSingle, logAllBox));

    // Initial state (handle back nav / old input)
    if (regAll?.checked || logAll?.checked) {
      const src = regAll?.checked ? regAll : logAll;
      const twin = regAll?.checked ? logAll : regAll;
      const hidden = regAll?.checked ? regCourseId : logCourseId;
      const sBox = regAll?.checked ? regSingle : logSingle;
      const aBox = regAll?.checked ? regAllBox : logAllBox;
      applyAllState(src, twin, hidden, sBox, aBox);
    } else {
      // Default: single-course summaries visible
      [regAllBox, logAllBox].forEach(b => b && b.classList.add('d-none'));
      [regSingle, logSingle].forEach(b => b && b.classList.remove('d-none'));
    }

    // ===== Bullet-proof Login Submit =====
    document.addEventListener('click', function (e) {
      const btn = e.target && e.target.closest && e.target.closest('#loginSubmit');
      if (!btn) return;

      const form = document.getElementById('loginForm');
      if (!form) return;

      e.preventDefault();
      e.stopPropagation();
      if (e.stopImmediatePropagation) e.stopImmediatePropagation();

      try { if (form.reportValidity && !form.reportValidity()) return; } catch (_) {}

      if (form.dataset.submitting === '1') return;
      form.dataset.submitting = '1';
      try { form.submit(); }
      finally { form.dataset.submitting = '0'; }
    }, true); // capture phase
  });
</script>
@endsection
