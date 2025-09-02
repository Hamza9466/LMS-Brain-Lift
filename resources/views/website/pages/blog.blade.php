@extends('website.layouts.main')
@section('content')
<!-- ==================== Header End Here ==================== -->

    <!-- ==================== Breadcrumb Start Here ==================== -->
<section class="breadcrumb py-120 bg-main-25 position-relative z-1 overflow-hidden mb-0">
    <img src="assets/images/shapes/shape1.png" alt="" class="shape one animation-rotation d-md-block d-none">
    <img src="assets/images/shapes/shape2.png" alt="" class="shape two animation-scalation d-md-block d-none">
    <img src="assets/images/shapes/shape3.png" alt="" class="shape eight animation-walking d-md-block d-none">
    <img src="assets/images/shapes/shape5.png" alt="" class="shape six animation-walking d-md-block d-none">
    <img src="assets/images/shapes/shape4.png" alt="" class="shape four animation-scalation">
    <img src="assets/images/shapes/shape4.png" alt="" class="shape nine animation-scalation">
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="breadcrumb__wrapper">
                    <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Blog Grid</h1>
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
                            <span class="text-main-two-600"> Blog Grid </span> 
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ==================== Breadcrumb End Here ==================== -->

    <!-- ================================ Blog Page Section Start =================================== -->
     <div class="blog-page-section py-120">
        <div class="container">
            <div class="flex-between gap-16 flex-wrap mb-40">
                <span class="text-neutral-500">Showing 9 of 600 Results </span>
                <div class="flex-align gap-16">
                    <div class="flex-align gap-8">
                        <span class="text-neutral-500 flex-shrink-0">Sort By :</span>
                        <select class="form-select ps-20 pe-28 py-8 fw-medium rounded-pill bg-main-25 border border-neutral-30 text-neutral-700">
                            <option value="1">Newest</option>
                            <option value="1">Trending</option>
                            <option value="1">Popular</option>
                        </select>
                    </div>
                    <button type="button" class="list-bar-btn text-xl w-40 h-40 bg-main-600 text-white rounded-8 flex-center d-lg-none">
                        <i class="ph-bold ph-funnel"></i>
                    </button>
                </div>
            </div>
            <div class="row gy-4">
                <div class="col-lg-4 col-sm-6">
                    <div class="scale-hover-item bg-main-25 rounded-16 p-12 h-100 border border-neutral-30">
                        <div class="course-item__thumb rounded-12 overflow-hidden position-relative">
                            <a href="blog-details.html" class="w-100 h-100">
                                <img src="assets/images/thumbs/blog-two-img1.png" alt="Course Image" class="scale-hover-item__img rounded-12 cover-img transition-2">
                            </a>
                            <div class="position-absolute inset-inline-end-0 inset-block-end-0 me-16 mb-16 py-12 px-24 rounded-8 bg-main-three-600 text-white fw-medium">
                                <h3 class="mb-0 text-white fw-medium">21</h3>
                                DEC
                            </div>
                        </div>
                        <div class="pt-32 pb-24 px-16 position-relative">
                            <h4 class="mb-28">
                                <a href="blog-details.html" class="link text-line-2">Navigating the Job Market: Advice for Graduates</a>
                            </h4>
                            <div class="flex-align gap-14 flex-wrap my-20">
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-user-circle"></i></span>
                                    <span class="text-neutral-500 text-lg">By Admin</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph-bold ph-eye"></i></span>
                                    <span class="text-neutral-500 text-lg">1.6k</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-chat-dots"></i></span>
                                    <span class="text-neutral-500 text-lg">24</span>
                                </div>
                            </div>
                            <div class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
                                <a href="{{ route('BlogDetail') }}" class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold" tabindex="0">
                                    Read More
                                    <i class="ph ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>                            
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="scale-hover-item bg-main-25 rounded-16 p-12 h-100 border border-neutral-30">
                        <div class="course-item__thumb rounded-12 overflow-hidden position-relative">
                            <a href="blog-details.html" class="w-100 h-100">
                                <img src="assets/images/thumbs/blog-two-img2.png" alt="Course Image" class="scale-hover-item__img rounded-12 cover-img transition-2">
                            </a>
                            <div class="position-absolute inset-inline-end-0 inset-block-end-0 me-16 mb-16 py-12 px-24 rounded-8 bg-main-three-600 text-white fw-medium">
                                <h3 class="mb-0 text-white fw-medium">21</h3>
                                DEC
                            </div>
                        </div>
                        <div class="pt-32 pb-24 px-16 position-relative">
                            <h4 class="mb-28">
                                <a href="blog-details.html" class="link text-line-2">The Importance of Diversity in Higher Education</a>
                            </h4>
                            <div class="flex-align gap-14 flex-wrap my-20">
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-user-circle"></i></span>
                                    <span class="text-neutral-500 text-lg">By Admin</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph-bold ph-eye"></i></span>
                                    <span class="text-neutral-500 text-lg">1.6k</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-chat-dots"></i></span>
                                    <span class="text-neutral-500 text-lg">24</span>
                                </div>
                            </div>
                            <div class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
                                <a href="blog-details.html" class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold" tabindex="0">
                                    Read More
                                    <i class="ph ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>                        
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="scale-hover-item bg-main-25 rounded-16 p-12 h-100 border border-neutral-30">
                        <div class="course-item__thumb rounded-12 overflow-hidden position-relative">
                            <a href="blog-details.html" class="w-100 h-100">
                                <img src="assets/images/thumbs/blog-two-img3.png" alt="Course Image" class="scale-hover-item__img rounded-12 cover-img transition-2">
                            </a>
                            <div class="position-absolute inset-inline-end-0 inset-block-end-0 me-16 mb-16 py-12 px-24 rounded-8 bg-main-three-600 text-white fw-medium">
                                <h3 class="mb-0 text-white fw-medium">21</h3>
                                DEC
                            </div>
                        </div>
                        <div class="pt-32 pb-24 px-16 position-relative">
                            <h4 class="mb-28">
                                <a href="blog-details.html" class="link text-line-2">10 Tips for Successful Online Learning</a>
                            </h4>
                            <div class="flex-align gap-14 flex-wrap my-20">
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-user-circle"></i></span>
                                    <span class="text-neutral-500 text-lg">By Admin</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph-bold ph-eye"></i></span>
                                    <span class="text-neutral-500 text-lg">1.6k</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-chat-dots"></i></span>
                                    <span class="text-neutral-500 text-lg">24</span>
                                </div>
                            </div>
                            <div class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
                                <a href="blog-details.html" class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold" tabindex="0">
                                    Read More
                                    <i class="ph ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>                        
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="scale-hover-item bg-main-25 rounded-16 p-12 h-100 border border-neutral-30">
                        <div class="course-item__thumb rounded-12 overflow-hidden position-relative">
                            <a href="blog-details.html" class="w-100 h-100">
                                <img src="assets/images/thumbs/blog-two-img4.png" alt="Course Image" class="scale-hover-item__img rounded-12 cover-img transition-2">
                            </a>
                            <div class="position-absolute inset-inline-end-0 inset-block-end-0 me-16 mb-16 py-12 px-24 rounded-8 bg-main-three-600 text-white fw-medium">
                                <h3 class="mb-0 text-white fw-medium">21</h3>
                                DEC
                            </div>
                        </div>
                        <div class="pt-32 pb-24 px-16 position-relative">
                            <h4 class="mb-28">
                                <a href="blog-details.html" class="link text-line-2">How to Stay Motivated While Studying from Home</a>
                            </h4>
                            <div class="flex-align gap-14 flex-wrap my-20">
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-user-circle"></i></span>
                                    <span class="text-neutral-500 text-lg">By Admin</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph-bold ph-eye"></i></span>
                                    <span class="text-neutral-500 text-lg">1.6k</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-chat-dots"></i></span>
                                    <span class="text-neutral-500 text-lg">24</span>
                                </div>
                            </div>
                            <div class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
                                <a href="blog-details.html" class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold" tabindex="0">
                                    Read More
                                    <i class="ph ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>                            
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="scale-hover-item bg-main-25 rounded-16 p-12 h-100 border border-neutral-30">
                        <div class="course-item__thumb rounded-12 overflow-hidden position-relative">
                            <a href="blog-details.html" class="w-100 h-100">
                                <img src="assets/images/thumbs/blog-two-img5.png" alt="Course Image" class="scale-hover-item__img rounded-12 cover-img transition-2">
                            </a>
                            <div class="position-absolute inset-inline-end-0 inset-block-end-0 me-16 mb-16 py-12 px-24 rounded-8 bg-main-three-600 text-white fw-medium">
                                <h3 class="mb-0 text-white fw-medium">21</h3>
                                DEC
                            </div>
                        </div>
                        <div class="pt-32 pb-24 px-16 position-relative">
                            <h4 class="mb-28">
                                <a href="blog-details.html" class="link text-line-2">Mastering Python: Beginner to Advanced Tips</a>
                            </h4>
                            <div class="flex-align gap-14 flex-wrap my-20">
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-user-circle"></i></span>
                                    <span class="text-neutral-500 text-lg">By Admin</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph-bold ph-eye"></i></span>
                                    <span class="text-neutral-500 text-lg">1.6k</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-chat-dots"></i></span>
                                    <span class="text-neutral-500 text-lg">24</span>
                                </div>
                            </div>
                            <div class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
                                <a href="blog-details.html" class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold" tabindex="0">
                                    Read More
                                    <i class="ph ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>                        
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="scale-hover-item bg-main-25 rounded-16 p-12 h-100 border border-neutral-30">
                        <div class="course-item__thumb rounded-12 overflow-hidden position-relative">
                            <a href="blog-details.html" class="w-100 h-100">
                                <img src="assets/images/thumbs/blog-two-img6.png" alt="Course Image" class="scale-hover-item__img rounded-12 cover-img transition-2">
                            </a>
                            <div class="position-absolute inset-inline-end-0 inset-block-end-0 me-16 mb-16 py-12 px-24 rounded-8 bg-main-three-600 text-white fw-medium">
                                <h3 class="mb-0 text-white fw-medium">21</h3>
                                DEC
                            </div>
                        </div>
                        <div class="pt-32 pb-24 px-16 position-relative">
                            <h4 class="mb-28">
                                <a href="blog-details.html" class="link text-line-2">Balancing Work and Study: Strategies for Success</a>
                            </h4>
                            <div class="flex-align gap-14 flex-wrap my-20">
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-user-circle"></i></span>
                                    <span class="text-neutral-500 text-lg">By Admin</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph-bold ph-eye"></i></span>
                                    <span class="text-neutral-500 text-lg">1.6k</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-chat-dots"></i></span>
                                    <span class="text-neutral-500 text-lg">24</span>
                                </div>
                            </div>
                            <div class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
                                <a href="blog-details.html" class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold" tabindex="0">
                                    Read More
                                    <i class="ph ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>                        
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="scale-hover-item bg-main-25 rounded-16 p-12 h-100 border border-neutral-30">
                        <div class="course-item__thumb rounded-12 overflow-hidden position-relative">
                            <a href="blog-details.html" class="w-100 h-100">
                                <img src="assets/images/thumbs/blog-two-img7.png" alt="Course Image" class="scale-hover-item__img rounded-12 cover-img transition-2">
                            </a>
                            <div class="position-absolute inset-inline-end-0 inset-block-end-0 me-16 mb-16 py-12 px-24 rounded-8 bg-main-three-600 text-white fw-medium">
                                <h3 class="mb-0 text-white fw-medium">21</h3>
                                DEC
                            </div>
                        </div>
                        <div class="pt-32 pb-24 px-16 position-relative">
                            <h4 class="mb-28">
                                <a href="blog-details.html" class="link text-line-2">The Importance of Lifelong Learning in Today's World</a>
                            </h4>
                            <div class="flex-align gap-14 flex-wrap my-20">
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-user-circle"></i></span>
                                    <span class="text-neutral-500 text-lg">By Admin</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph-bold ph-eye"></i></span>
                                    <span class="text-neutral-500 text-lg">1.6k</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-chat-dots"></i></span>
                                    <span class="text-neutral-500 text-lg">24</span>
                                </div>
                            </div>
                            <div class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
                                <a href="blog-details.html" class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold" tabindex="0">
                                    Read More
                                    <i class="ph ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>                            
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="scale-hover-item bg-main-25 rounded-16 p-12 h-100 border border-neutral-30">
                        <div class="course-item__thumb rounded-12 overflow-hidden position-relative">
                            <a href="blog-details.html" class="w-100 h-100">
                                <img src="assets/images/thumbs/blog-two-img8.png" alt="Course Image" class="scale-hover-item__img rounded-12 cover-img transition-2">
                            </a>
                            <div class="position-absolute inset-inline-end-0 inset-block-end-0 me-16 mb-16 py-12 px-24 rounded-8 bg-main-three-600 text-white fw-medium">
                                <h3 class="mb-0 text-white fw-medium">21</h3>
                                DEC
                            </div>
                        </div>
                        <div class="pt-32 pb-24 px-16 position-relative">
                            <h4 class="mb-28">
                                <a href="blog-details.html" class="link text-line-2">Effective Time Management for Students</a>
                            </h4>
                            <div class="flex-align gap-14 flex-wrap my-20">
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-user-circle"></i></span>
                                    <span class="text-neutral-500 text-lg">By Admin</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph-bold ph-eye"></i></span>
                                    <span class="text-neutral-500 text-lg">1.6k</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-chat-dots"></i></span>
                                    <span class="text-neutral-500 text-lg">24</span>
                                </div>
                            </div>
                            <div class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
                                <a href="blog-details.html" class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold" tabindex="0">
                                    Read More
                                    <i class="ph ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>                        
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="scale-hover-item bg-main-25 rounded-16 p-12 h-100 border border-neutral-30">
                        <div class="course-item__thumb rounded-12 overflow-hidden position-relative">
                            <a href="blog-details.html" class="w-100 h-100">
                                <img src="assets/images/thumbs/blog-two-img9.png" alt="Course Image" class="scale-hover-item__img rounded-12 cover-img transition-2">
                            </a>
                            <div class="position-absolute inset-inline-end-0 inset-block-end-0 me-16 mb-16 py-12 px-24 rounded-8 bg-main-three-600 text-white fw-medium">
                                <h3 class="mb-0 text-white fw-medium">21</h3>
                                DEC
                            </div>
                        </div>
                        <div class="pt-32 pb-24 px-16 position-relative">
                            <h4 class="mb-28">
                                <a href="blog-details.html" class="link text-line-2">The Benefits of Learning a New Language Online</a>
                            </h4>
                            <div class="flex-align gap-14 flex-wrap my-20">
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-user-circle"></i></span>
                                    <span class="text-neutral-500 text-lg">By Admin</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph-bold ph-eye"></i></span>
                                    <span class="text-neutral-500 text-lg">1.6k</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-chat-dots"></i></span>
                                    <span class="text-neutral-500 text-lg">24</span>
                                </div>
                            </div>
                            <div class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
                                <a href="blog-details.html" class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold" tabindex="0">
                                    Read More
                                    <i class="ph ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>                        
                </div>
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
     </div>
    <!-- ================================ Blog Page Section End =================================== -->
    
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
                                <img src="assets/images/thumbs/certificate-img.png" alt="" data-tilt data-tilt-max="8" data-tilt-speed="500" data-tilt-perspective="5000" data-tilt-full-page-listening>
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