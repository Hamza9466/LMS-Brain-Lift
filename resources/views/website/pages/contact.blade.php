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
                    <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Contact</h1>
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
                            <span class="text-main-two-600"> Contact </span> 
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ==================== Breadcrumb End Here ==================== -->

    <!-- =============================== Contact Section Start ================================== -->
    <section class="contact py-120">
        <div class="container">
            <div class="section-heading text-center">
            <div class="flex-align d-inline-flex gap-8 mb-16">
                <span class="text-main-600 text-2xl d-flex"><i class="ph-bold ph-book"></i></span>
                <h5 class="text-main-600 mb-0">Get In Touch</h5>
            </div>
            <h2 class="mb-24">Let us help you</h2>
            <p class="">Our platform is built on the principles of innovation, quality, and inclusivity, aiming to provide a seamless learning</p>
        </div>
            <div class="row gy-4">
                <div class="col-xl-4 col-md-6">
                    <div class="contact-item bg-main-25 border border-neutral-30 rounded-12 px-32 py-40 d-flex align-items-start gap-24 hover-bg-main-600 transition-2 hover-border-main-600">
                        <span class="contact-item__icon w-60 h-60 text-32 flex-center rounded-circle bg-main-600 text-white flex-shrink-0">
                            <i class="ph ph-map-pin-line"></i>
                        </span>
                        <div class="flex-grow-1">
                            <h4 class="mb-12">Main Office</h4>
                            <p class="text-neutral-500">2972 Westheimer Rd. Santa Ana, Illinois 85486 </p>
                            <a href="javascript:void(0)" class="text-main-600 fw-semibold text-decoration-underline mt-16">Find Location</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="contact-item bg-main-25 border border-neutral-30 rounded-12 px-32 py-40 d-flex align-items-start gap-24 hover-bg-main-600 transition-2 hover-border-main-600">
                        <span class="contact-item__icon w-60 h-60 text-32 flex-center rounded-circle bg-main-600 text-white flex-shrink-0">
                            <i class="ph ph-envelope-open"></i>
                        </span>
                        <div class="flex-grow-1">
                            <h4 class="mb-12">Email Address</h4>
                            <p class="text-neutral-500">infoexample@gmail.com</p>
                            <p class="text-neutral-500">example@gmail.com</p>
                            <a href="mailto:infoexample@gmail.com" class="text-main-600 fw-semibold text-decoration-underline mt-16">Get In Touch</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="contact-item bg-main-25 border border-neutral-30 rounded-12 px-32 py-40 d-flex align-items-start gap-24 hover-bg-main-600 transition-2 hover-border-main-600">
                        <span class="contact-item__icon w-60 h-60 text-32 flex-center rounded-circle bg-main-600 text-white flex-shrink-0">
                            <i class="ph ph-phone-call"></i>
                        </span>
                        <div class="flex-grow-1">
                            <h4 class="mb-12">Phone Number</h4>
                            <p class="text-neutral-500">(505) 555-0125</p>
                            <p class="text-neutral-500">(406) 555-0120</p>
                            <a href="tel:(406)555-0120" class="text-main-600 fw-semibold text-decoration-underline mt-16">Contact Us Today!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- =============================== Contact Section End ================================== -->
     
    <!-- ====================== Contact Form Section Start ========================= -->
    <section class="contact-form-section py-240 bg-main-25 position-relative z-1">
       <img src="{{ asset('assets/website/images/contact/wave-bg.png') }}"
     alt=""
     class="position-absolute top-0 start-0 w-100 h-100 z-n1 d-lg-block d-none">

        <div class="container">
            <div class="row gy-5 align-items-center">
                <div class="col-xl-7 col-lg-6 pe-lg-5">
                    <div class="mb-40 md-xl-5">
                        <div class="flex-align d-inline-flex gap-8 mb-16">
                            <span class="text-main-600 text-2xl d-flex"><i class="ph-bold ph-book"></i></span>
                            <h5 class="text-main-600 mb-0">Contact Us</h5>
                        </div>
                        <h2 class="mb-24">Have questions? don't hesitate to contact us</h2>
                        <p class="text-neutral-500 text-line-3 max-w-636">We are passionate about transforming lives through education. Founded with a vision to make learning accessible to all, we believe in the power of knowledge to unlock opportunities and shape the future.</p>
                    </div>
                    <div class="flex-align gap-40 flex-wrap">
                       <div class="enrolled-students mt-12 d-block">
  <img src="{{ asset('assets/website/images/contact/enroll-student-img1.png') }}" alt="" class="w-48 h-48 rounded-circle object-fit-cover transition-2">
  <img src="{{ asset('assets/website/images/contact/enroll-student-img2.png') }}" alt="" class="w-48 h-48 rounded-circle object-fit-cover transition-2">
  <img src="{{ asset('assets/website/images/contact/enroll-student-img3.png') }}" alt="" class="w-48 h-48 rounded-circle object-fit-cover transition-2">
  <img src="{{ asset('assets/website/images/contact/enroll-student-img4.png') }}" alt="" class="w-48 h-48 rounded-circle object-fit-cover transition-2">
  <img src="{{ asset('assets/website/images/contact/enroll-student-img5.png') }}" alt="" class="w-48 h-48 rounded-circle object-fit-cover transition-2">
  <img src="{{ asset('assets/website/images/contact/enroll-student-img6.png') }}" alt="" class="w-48 h-48 rounded-circle object-fit-cover transition-2">
</div>

                        <div class="">
                            <ul class="flex-align gap-4 mb-10">
                                <li class="text-warning-600 text-2xl d-flex"><i class="ph-fill ph-star"></i></li>
                                <li class="text-warning-600 text-2xl d-flex"><i class="ph-fill ph-star"></i></li>
                                <li class="text-warning-600 text-2xl d-flex"><i class="ph-fill ph-star"></i></li>
                                <li class="text-warning-600 text-2xl d-flex"><i class="ph-fill ph-star"></i></li>
                                <li class="text-warning-600 text-2xl d-flex"><i class="ph-fill ph-star-half"></i></li>
                            </ul>
                            <span class="text-neutral-700 fw-medium"> 2.5k+ reviews (4.95 of 5)</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-6">
                    <div class="p-24 bg-white rounded-12 box-shadow-md">
                        <div class="border border-neutral-30 rounded-8 bg-main-25 p-24">
                           <form action="{{ route('contact.store') }}" method="POST" id="contactForm" novalidate>
  @csrf

  @if (session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif
  @if ($errors->any())
    <div class="alert alert-danger mb-3">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <h4 class="mb-0">Get In Touch</h4>
  <span class="d-block border border-neutral-30 my-24 border-dashed"></span>

  <div class="mb-24">
    <label for="name" class="text-neutral-700 text-lg fw-medium mb-12">Name</label>
    <input
      type="text"
      id="name"
      name="name"
      value="{{ old('name') }}"
      required
      class="common-input rounded-pill border-transparent focus-border-main-600"
      placeholder="Enter Name..."
    >
  </div>

  <div class="mb-24">
    <label for="email" class="text-neutral-700 text-lg fw-medium mb-12">Email</label>
    <input
      type="email"
      id="email"
      name="email"
      value="{{ old('email') }}"
      required
      class="common-input rounded-pill border-transparent focus-border-main-600"
      placeholder="Enter Email..."
    >
  </div>

  <div class="mb-24">
    <label for="message" class="text-neutral-700 text-lg fw-medium mb-12">Message</label>
    <textarea
      id="message"
      name="message"
      required
      class="common-input rounded-24 border-transparent focus-border-main-600 h-110"
      placeholder="Enter Your Message..."
    >{{ old('message') }}</textarea>
  </div>

  <div class="mb-0">
    <button type="submit" class="btn btn-main rounded-pill flex-center gap-8 mt-40">
      Send Message
      <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
    </button>
  </div>
</form>

<script>
  // optional: prevent double submit
  document.getElementById('contactForm')?.addEventListener('submit', function(e){
    const btn = this.querySelector('button[type="submit"]');
    if (btn) { btn.disabled = true; setTimeout(() => btn.disabled = false, 4000); }
  });
</script>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ====================== Contact Form Section End ========================= -->
    
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
                                <h2 class="text-white mb-40 fw-medium wow bounceIn">Get Quality Skills Certificate From the EduAll</h2>
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
@endsection