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
                    <h1 class="breadcrumb__title display-4 fw-semibold text-center"> About Us</h1>
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
                            <span class="text-main-two-600"> About Us </span> 
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ==================== Breadcrumb End Here ==================== -->

    <!-- ================================ About Section Start ==================================== -->
 <section class="about py-120 position-relative z-1 mash-bg-main mash-bg-main-two">
<img src="{{ asset('assets/website/images/about/shape2.png') }}" alt="" class="shape one animation-scalation">
<img src="{{ asset('assets/website/images/about/shape6.png') }}" alt="" class="shape four animation-scalation">


    <div class="position-relative">
        <div class="container">
            <div class="row gy-xl-0 gy-5 flex-wrap-reverse align-items-center">
                <div class="col-xl-6">
                    <div class="about-thumbs position-relative pe-lg-5">
                       <img src="{{ asset('assets/website/images/about/shape7.png') }}" alt="" class="shape seven animation-scalation">

                        <div class="offer-message px-24 py-12 rounded-12 bg-main-two-50 fw-medium flex-align d-inline-flex gap-16 border border-neutral-30 animation-upDown">
                            <span class="flex-shrink-0 w-48 h-48 bg-main-two-600 text-white text-2xl flex-center rounded-circle"><i class="ph ph-watch"></i></span>
                            <div>
                                <h6 class="mb-4">20% OFF</h6>
                                <span class="text-neutral-500">For All Courses</span>
                            </div>
                        </div>
                        <div class="row gy-4">
                            <div class="col-sm-6">
                               <img src="{{ asset('assets/website/images/about/about-img1.png') }}" alt="" class="rounded-12 w-100" data-tilt data-tilt-max="15" data-tilt-speed="500" data-tilt-perspective="5000" data-tilt-full-page-listening>

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
                                <img src="{{ asset('assets/website/images/about/about-img2.png') }}" alt="" class="rounded-12 w-100" data-tilt data-tilt-max="15" data-tilt-speed="500" data-tilt-perspective="5000" data-tilt-full-page-listening>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="about-content">
                        <div class="mb-40">
                            <div class="flex-align gap-8 mb-16 wow bounceInDown">
                                <span class="w-8 h-8 bg-main-600 rounded-circle"></span>
                                <h5 class="text-main-600 mb-0 ">About Brain Lift</h5>
                            </div>
                            <h2 class="mb-24 wow bounceIn">The Place Where You Can Achieve</h2>
                            <p class="text-neutral-500 text-line-2 wow bounceInUp">Welcome to Brain Lift, where learning meets opportunity. Whether you’re a student, freelancer, or professional, we help you gain practical skills and grow your career.</p>
                        </div>
    
                        <div class="flex-align align-items-start gap-28 mb-32" data-aos="fade-left" data-aos-duration="200">
                            <span class="w-80 h-80 bg-main-25 border border-neutral-30 flex-center rounded-circle flex-shrink-0">
                               <img src="{{ asset('assets/website/images/about/about-img1.png') }}" alt="">

                            </span>
                            <div class="flex-grow-1">
                                <h4 class="text-neutral-500 mb-12">Our Mission</h4>
                                <p class="text-neutral-500">Driven by a team of passionate trainers and industry experts, Brain Lift is dedicated to providing step-by-step digital skills training that leads to growth, confidence, and financial independence.</p>
                            </div>
                        </div>
                        <div class="flex-align align-items-start gap-28 mb-0" data-aos="fade-left" data-aos-duration="400">
                            <span class="w-80 h-80 bg-main-25 border border-neutral-30 flex-center rounded-circle flex-shrink-0">
                               <img src="{{ asset('assets/website/images/about/about-img1.png') }}" alt="">

                            </span>
                            <div class="flex-grow-1">
                                <h4 class="text-neutral-500 mb-12">Our Vision</h4>
                                <p class="text-neutral-500">We envision a world where every learner can upskill, earn, and grow through practical digital education. At Brain Lift, we guide you at every step so you can explore new horizons with confidence. </p>
                            </div>
                        </div>
    
                        <div class="flex-align flex-wrap gap-32 pt-40 border-top border-neutral-50 mt-40 border-dashed border-0" data-aos="fade-left" data-aos-duration="600">
                            <a href="{{ route('CourseGrid') }}" class="btn btn-main rounded-pill flex-align gap-8">
                                Read More
                                <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                            </a>
                            <div class="flex-align gap-20">
                               <img src="{{ asset('assets/website/images/about/ceo-img.png') }}" alt="" class="w-52 h-52 rounded-circle object-fit-cover flex-shrink-0">

                                <div class="flex-grow-1">
                                    <span class="mb-4">
                                  <img src="{{ asset('assets/website/images/about/signature.png') }}" alt="">

                                    </span>
                                    <span class="text-sm d-block">Ch. M. Usham Ilyas
</span>
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
<img src="{{ asset('assets/website/images/about/shapes/shape2.png') }}" alt="" class="shape one animation-scalation">
<img src="{{ asset('assets/website/images/about/shapes/shape2.png') }}" alt="" class="shape six animation-scalation">


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
                    <a href="{{ route('about') }}" class="btn btn-main rounded-pill flex-align d-inline-flex gap-8">
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
                       <img src="{{ asset('assets/website/images/about/stars.png') }}" alt="">

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
  <img src="{{ asset('assets/website/images/about/enroll-student-img1.png') }}" alt="" class="w-48 h-48 rounded-circle object-fit-cover">
  <img src="{{ asset('assets/website/images/about/enroll-student-img2.png') }}" alt="" class="w-48 h-48 rounded-circle object-fit-cover">
  <img src="{{ asset('assets/website/images/about/enroll-student-img3.png') }}" alt="" class="w-48 h-48 rounded-circle object-fit-cover">
  <img src="{{ asset('assets/website/images/about/enroll-student-img4.png') }}" alt="" class="w-48 h-48 rounded-circle object-fit-cover">
  <img src="{{ asset('assets/website/images/about/enroll-student-img5.png') }}" alt="" class="w-48 h-48 rounded-circle object-fit-cover">
  <img src="{{ asset('assets/website/images/about/enroll-student-img6.png') }}" alt="" class="w-48 h-48 rounded-circle object-fit-cover">
</div>

                </div>

                <div class="text-end" data-aos="zoom-out">
                    <div class="d-sm-inline-block d-block position-relative">
                        <img src="{{ asset('assets/website/images/about/choose-us-img1.png') }}" alt="" class="choose-us__img rounded-12" data-tilt data-tilt-max="16" data-tilt-speed="500" data-tilt-perspective="5000" data-tilt-full-page-listening>

                        <span class="shadow-main-two w-80 h-80 flex-center bg-main-two-600 rounded-circle position-absolute inset-block-start-0 inset-inline-start-0 mt-40 ms--40 animation-upDown">
                           <img src="{{ asset('assets/website/images/about/book.png') }}" alt="">

                        </span>
                    </div>
                </div>
                <div class="animation-video"  data-aos="zoom-in">
                    <img src="{{ asset('assets/website/images/about/choose-us-img2.png') }}" alt="" class="border border-white rounded-circle border-3" data-tilt>

                    <a href="https://www.youtube.com/watch?v=MFLVmAE4cqg" class="play-button w-48 h-48 flex-center bg-main-600 text-white rounded-circle text-xl position-absolute top-50 start-50 translate-middle">
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
<img src="{{ asset('assets/website/images/about/shape2.png') }}" alt="" class="shape six animation-scalation">
<img src="{{ asset('assets/website/images/about/shape3.png') }}" alt="" class="shape four animation-rotation">

    
    <div class="container">
        <div class="row gy-5">
            <div class="col-lg-6">
                <div class="testimonials__thumbs-slider pe-lg-5 me-xxl-5">
                    <div class="testimonials__thumbs wow bounceIn" data-tilt data-tilt-max="15" data-tilt-speed="500" data-tilt-perspective="5000" data-tilt-full-page-listening>
                       <img src="{{ asset('assets/website/images/about/testimonial-img1.png') }}" alt="">

                    </div>
                    <div class="testimonials__thumbs wow bounceIn" data-tilt data-tilt-max="15" data-tilt-speed="500" data-tilt-perspective="5000" data-tilt-full-page-listening>
                       <img src="{{ asset('assets/website/images/about/testimonial-img2.png') }}" alt="">
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
                        <h2 class="mb-24 wow bounceIn">Testimonials from Happy Learners for Brain LIft</h2>
                        <p class="text-neutral-500 text-line-2 wow bounceInUp">16+ million Students are already learning on Brain Lift Platform</p>
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
                            <p class="text-neutral-700" data-aos="fade-left" data-aos-duration="1200">"Enrolling in courses at LMS Brain Lift was one of the best decisions I've made for my career. The flexibility of the online learning platform allowed me to study at my own pace while balancing my work”</p>
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
                            <p class="text-neutral-700" data-aos="fade-left" data-aos-duration="1200">"Signing up for courses at Brain Lift was quite possibly of the best choice I've made for my vocation. The adaptability of the internet learning stage permitted me to learn at my own speed while adjusting my work"</p>
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
    
    <!-- ========================== Brand Section Start =========================== -->
 <div class="brand wow fadeInUpBig" data-wow-duration="1s" data-wow-delay=".5s">
    <div class="container container--lg">
        <div class="brand-box py-80 px-16 ">
            <h5 class="mb-40 text-center text-neutral-500">TRUSTED BY OVER 17,300 GREAT TEAMS</h5>
            <div class="container">
                <div class="brand-slider">
                   <div class="brand-slider__item px-24">
    <img src="{{ asset('assets/website/images/thumbs/brand-img1.png') }}" alt="">
</div>
<div class="brand-slider__item px-24">
    <img src="{{ asset('assets/website/images/thumbs/brand-img2.png') }}" alt="">
</div>
<div class="brand-slider__item px-24">
    <img src="{{ asset('assets/website/images/about/brand-img3.png') }}" alt="">
</div>
<div class="brand-slider__item px-24">
    <img src="{{ asset('assets/website/images/about/brand-img4.png') }}" alt="">
</div>
<div class="brand-slider__item px-24">
    <img src="{{ asset('assets/website/images/about/brand-img5.png') }}" alt="">
</div>
<div class="brand-slider__item px-24">
    <img src="{{ asset('assets/website/images/about/brand-img6.png') }}" alt="">
</div>
<div class="brand-slider__item px-24">
    <img src="{{ asset('assets/website/images/about/brand-img7.png') }}" alt="">
</div>
<div class="brand-slider__item px-24">
    <img src="{{ asset('assets/website/images/about/brand-img3.png') }}" alt="">
</div>

                </div>
            </div>
        </div>
    </div>
 </div>
<!-- ========================== Brand Section End =========================== -->

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
                                <img src="{{ asset('assets/website/images/about/certificate-img.png') }}" alt="" data-tilt data-tilt-max="8" data-tilt-speed="500" data-tilt-perspective="5000" data-tilt-full-page-listening>
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