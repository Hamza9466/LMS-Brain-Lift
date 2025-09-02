@extends('website.layouts.main')
@section('content')
<!-- ==================== Header End Here ==================== -->

    <!-- ==================== Breadcrumb Start Here ==================== -->
<section class="breadcrumb py-120 bg-main-25 position-relative z-1 overflow-hidden mb-0">
        <img src="{{ asset('assets/website/images/home/shape1.png') }}" alt="" class="shape one animation-rotation">
<img src="{{ asset('assets/website/images/home/shape2.png') }}" alt="" class="shape two animation-scalation">
<img src="{{ asset('assets/website/images/home/shape3.png') }}" alt="" class="shape three animation-walking">
<img src="{{ asset('assets/website/images/home/shape4.png') }}" alt="" class="shape four animation-scalation">
<img src="{{ asset('assets/website/images/home/shape5.png') }}" alt="" class="shape five animation-walking">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="breadcrumb__wrapper">
                    <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Sign In</h1>
                    <ul class="breadcrumb__list d-flex align-items-center justify-content-center gap-4">
                        <li class="breadcrumb__item">
                            <a href="index.html" class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium"> 
                                <i class="text-lg d-inline-flex ph-bold ph-house"></i> Home</a>
                         </li>
                        <li class="breadcrumb__item">
                            <i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i>
                        </li>
                        <li class="breadcrumb__item">
                            <a href="course.html" class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium"> </a> 
                        </li>
                        <li class="breadcrumb__item d-none">
                            <i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i>
                        </li>
                        <li class="breadcrumb__item"> 
                            <span class="text-main-two-600"> Sign In </span> 
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ==================== Breadcrumb End Here ==================== -->

    <!-- ============================== Tutor Details Section Start ============================== -->
    <div class="account py-120 position-relative">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-6">
                    <div class="bg-main-25 border border-neutral-30 rounded-8 p-32">
                        <div class="mb-40">
                            <h3 class="mb-16 text-neutral-500">Welcome Back!</h3>
                            <p class="text-neutral-500">Sign in to your account and join us</p>
                        </div>
                       {{-- Login Form --}}
@if(session('error'))
  <div class="alert alert-danger mb-16">{{ session('error') }}</div>
@endif
@if(session('success'))
  <div class="alert alert-success mb-16">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('login.post') }}" novalidate>
    @csrf

    <div class="mb-24">
        <label for="email" class="fw-medium text-lg text-neutral-500 mb-16">Enter Your Email ID</label>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            required
            autocomplete="email"
            autofocus
            class="common-input rounded-pill @error('email') is-invalid @enderror"
            placeholder="Enter Your Email..."
        >
        @error('email') <small class="invalid-feedback d-block">{{ $message }}</small> @enderror
    </div>

    <div class="mb-16">
        <label for="password" class="fw-medium text-lg text-neutral-500 mb-16">Enter Your Password</label>
        <div class="position-relative">
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
                class="common-input rounded-pill pe-44 @error('password') is-invalid @enderror"
                placeholder="Enter Your Password..."
            >
            <button
                type="button"
                class="toggle-password position-absolute top-50 inset-inline-end-0 me-16 translate-middle-y ph-bold ph-eye-closed"
                data-target="#password"
                aria-label="Toggle password visibility"
            ></button>
        </div>
        @error('password') <small class="invalid-feedback d-block">{{ $message }}</small> @enderror
    </div>

    <div class="d-flex justify-content-between align-items-center mb-16">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">Remember me</label>
        </div>

        {{-- If you have password reset routes enabled --}}
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-warning-600 hover-text-decoration-underline">Forgot password?</a>
        @else
            <a href="javascript:void(0)" class="text-warning-600 hover-text-decoration-underline">Forgot password?</a>
        @endif
    </div>

    <div class="mb-16">
        <p class="text-neutral-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="fw-semibold text-main-600 hover-text-decoration-underline">Sign Up</a>
        </p>
    </div>

    <div class="mt-40">
        <button type="submit" class="btn btn-main rounded-pill flex-center gap-8 mt-40">
            Sign In
            <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
        </button>
    </div>
</form>

{{-- Tiny JS for eye toggle (no external deps) --}}
<script>
document.addEventListener('click', function (e) {
  const btn = e.target.closest('.toggle-password');
  if (!btn) return;
  const input = document.querySelector(btn.dataset.target);
  if (!input) return;
  input.type = input.type === 'password' ? 'text' : 'password';
  btn.classList.toggle('ph-eye');
  btn.classList.toggle('ph-eye-closed');
});
</script>

                    </div>
                </div>
                <div class="col-lg-6 d-lg-block d-none">
                    <div class="account-img">
                        <img src="{{ asset('assets/website/images/login/account-img.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================== Tutor Details Section End ============================== -->
    
    
<!-- ==================== Footer Start Here ==================== -->
@endsection