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
                    <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Sign Up</h1>
                    <ul class="breadcrumb__list d-flex align-items-center justify-content-center gap-4">
                        <li class="breadcrumb__item">
                            <a href="{{ route('home') }}" class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium"> 
                                <i class="text-lg d-inline-flex ph-bold ph-house"></i> Home</a>
                         </li>
                        <li class="breadcrumb__item">
                            <i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i>
                        </li>
                        <li class="breadcrumb__item">
                            <a href="{{ route('CourseGrid') }}" class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium"> </a> 
                        </li>
                        <li class="breadcrumb__item d-none">
                            <i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i>
                        </li>
                        <li class="breadcrumb__item"> 
                            <span class="text-main-two-600"> Sign Up </span> 
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
                            <h3 class="mb-16 text-neutral-500">Let's Get Started!</h3>
                            <p class="text-neutral-500">Please Enter your Email Address to Start your Online Application</p>
                        </div>
                   {{-- Flash + validation errors --}}
@if ($errors->any())
  <div class="alert alert-danger mb-3">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
@if(session('success'))
  <div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif

<form action="{{ route('register.post') }}" method="POST" novalidate>
    @csrf
    <div class="row gy-4">
        <div class="col-sm-6">
            <label for="fname" class="fw-medium text-lg text-neutral-500 mb-16">First Name</label>
            <input type="text" name="first_name" class="common-input rounded-pill @error('first_name') is-invalid @enderror"
                   id="fname" value="{{ old('first_name') }}" placeholder="Enter Your First Name" required>
            @error('first_name') <small class="invalid-feedback d-block">{{ $message }}</small> @enderror
        </div>

        <div class="col-sm-6">
            <label for="lname" class="fw-medium text-lg text-neutral-500 mb-16">Last Name</label>
            <input type="text" name="last_name" class="common-input rounded-pill @error('last_name') is-invalid @enderror"
                   id="lname" value="{{ old('last_name') }}" placeholder="Enter Your Last Name" required>
            @error('last_name') <small class="invalid-feedback d-block">{{ $message }}</small> @enderror
        </div>

        <div class="col-sm-12">
            <label for="email" class="fw-medium text-lg text-neutral-500 mb-16">Enter Your Email ID</label>
            <input type="email" name="email" class="common-input rounded-pill @error('email') is-invalid @enderror"
                   id="email" value="{{ old('email') }}" placeholder="Enter Your Email..." required>
            @error('email') <small class="invalid-feedback d-block">{{ $message }}</small> @enderror
        </div>

        <div class="col-sm-12">
            <label for="password" class="fw-medium text-lg text-neutral-500 mb-16">Password</label>
            <div class="position-relative">
                <input type="password" name="password"
                       class="common-input rounded-pill pe-44 @error('password') is-invalid @enderror"
                       id="password" placeholder="Enter Your Password..." required>
            </div>
            @error('password') <small class="invalid-feedback d-block">{{ $message }}</small> @enderror
        </div>

        {{-- REQUIRED for `confirmed` rule --}}
        <div class="col-sm-12">
            <label for="password_confirmation" class="fw-medium text-lg text-neutral-500 mb-16">Confirm Password</label>
            <input type="password" name="password_confirmation" class="common-input rounded-pill"
                   id="password_confirmation" placeholder="Re-enter Your Password..." required>
        </div>

        <div class="col-sm-12 mt-20">
            <button type="submit" class="btn btn-main rounded-pill flex-center gap-8">
                Sign Up
                <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
            </button>
        </div>
    </div>
</form>


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