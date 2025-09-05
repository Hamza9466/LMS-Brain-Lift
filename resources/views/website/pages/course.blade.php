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
                    <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Courses Grid View</h1>
                    <ul class="breadcrumb__list d-flex align-items-center justify-content-center gap-4">
                        <li class="breadcrumb__item">
                            <a href="{{ route('home') }}" class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium"> 
                                <i class="text-lg d-inline-flex ph-bold ph-house"></i> Home</a>
                         </li>
                        <li class="breadcrumb__item">
                            <i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i>
                        </li>
                        <li class="breadcrumb__item">
                            <a href="{{ route('CourseGrid') }}" class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium"> Courses</a> 
                        </li>
                        <li class="breadcrumb__item ">
                            <i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i>
                        </li>
                        <li class="breadcrumb__item"> 
                            <span class="text-main-two-600"> Grid View </span> 
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ==================== Breadcrumb End Here ==================== -->

    <!-- ============================== Course Grid View Section Start ============================== -->
    <section class="course-grid-view py-120">
        <div class="container">
            <div class="flex-between gap-16 flex-wrap mb-40">
                <span class="text-neutral-500">Showing 9 of 600 Results </span>
                {{-- <div class="flex-align gap-8">
                    <span class="text-neutral-500 flex-shrink-0">Sort By :</span>
                    <select class="form-select ps-20 pe-28 py-8 fw-semibold rounded-pill bg-main-25 border border-neutral-30 text-neutral-700">
                        <option value="1">Newest</option>
                        <option value="1">Trending</option>
                        <option value="1">Popular</option>
                    </select>
                </div> --}}
            </div>
            <div class="row gy-4">

{{-- Loop over the courses collection --}}
@forelse ($courses->sortByDesc('id') as $course)
    @php
        // Thumbnail (storage) → fallback image
        $thumb = $course->thumbnail
            ? asset('storage/'.$course->thumbnail)
            : asset('assets/images/thumbs/course-img1.png');

        // Duration from total_duration_minutes
        $mins = (int) ($course->total_duration_minutes ?? 0);
        $duration = $mins > 0 ? floor($mins / 60).'h '.($mins % 60).'m' : '—';

        // Lessons / Level
        $lessons = $course->total_lessons ?? 0;
        $level   = $course->level ?? '—';

        // Ratings (optional fields)
        $rating       = number_format((float)($course->rating_avg ?? 0), 1);
        $ratingCount  = (int)($course->rating_count ?? 0);

        // Price label (no $ symbol)
        $isFree     = (bool)($course->is_free ?? false);
        $finalPrice = $course->discount_price ?? $course->price;
        $priceLabel = $isFree ? 'Free' : ($finalPrice !== null ? number_format((float)$finalPrice, 2) : '—');

        // Link to details (expects slug)
        $detailUrl = route('CourseDetail', ['slug' => $course->slug]);
    @endphp

    <div class="col-lg-4 col-sm-6">
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
                                {{ $rating }}
                                <span class="text-neutral-100">({{ number_format($ratingCount) }})</span>
                            </span>
                        </div>

                        {{-- Show category instead of teacher/avatar --}}
                        <div class="flex-align gap-8">
                            <span class="text-neutral-700 text-2xl d-flex"><i class="ph ph-tag"></i></span>
                            <span class="text-neutral-700 text-lg fw-medium">
                                {{ optional($course->category)->name ?? 'Uncategorized' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
                    <h4 class="mb-0 text-main-two-600">{{ $priceLabel }}</h4>
                    <a href="{{ route('enroll.start', $course->id) }}" class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold">
                        Enroll Now
                        <i class="ph ph-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="text-center text-muted py-5">No courses found.</div>
    </div>
@endforelse




            </div>
            <ul class="pagination mt-40 flex-align gap-12 flex-wrap justify-content-center">
                <li class="page-item">
                    <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0" href="#"><i class="ph-bold ph-caret-left"></i></a>
                </li>
                <li class="page-item">
                    <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0" href="#">1</a>
                </li>
                <li class="page-item">
                    <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0" href="#">2</a>
                </li>
                <li class="page-item">
                    <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0" href="#">3</a>
                </li>
                <li class="page-item">
                    <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0" href="#">...</a>
                </li>
                <li class="page-item">
                    <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0" href="#"><i class="ph-bold ph-caret-right"></i></a>
                </li>
            </ul>
        </div>
    </section>
    <!-- ============================== Course Grid View Section End ============================== -->
    
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
@endsection