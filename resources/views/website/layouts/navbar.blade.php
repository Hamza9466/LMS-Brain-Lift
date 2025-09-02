
<body style="overflow-x: hidden">
    
<!--==================== Preloader Start ====================-->
  <div class="preloader">
    <img src="assets/images/icons/preloader.gif" alt="">
  </div>
<!--==================== Preloader End ====================-->

<!--==================== Overlay Start ====================-->
<div class="overlay"></div>
<!--==================== Overlay End ====================-->

<!--==================== Sidebar Overlay End ====================-->
<div class="side-overlay"></div>
<!--==================== Sidebar Overlay End ====================-->

<!-- ==================== Scroll to Top End Here ==================== -->
<div class="progress-wrap">
  <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
  </svg>
</div>
<!-- ==================== Scroll to Top End Here ==================== -->

<!-- ==================== Mobile Menu Start Here ==================== -->
<div class="mobile-menu scroll-sm d-lg-none d-block">
    <button type="button" class="close-button"><i class="ph ph-x"></i> </button>
    <div class="mobile-menu__inner">
        <a href="{{ route('home') }}" class="mobile-menu__logo">
<img src="{{ asset('assets/website/images/logo/logo.png') }}" alt="Logo" style="height: 60px;">
        </a>
        <div class="mobile-menu__menu">
            
<ul class="nav-menu flex-align nav-menu--mobile">
   <li class="nav-menu__item activePage">
    <a href="{{ route('home') }}" class="nav-menu__link">Home</a>
</li>

    <li class="nav-menu__item">
    <a href="{{ route('CourseGrid') }}" class="nav-menu__link">Courses</a>
</li>
    <li class="nav-menu__item">
    <a href="{{ route('about') }}" class="nav-menu__link">About</a>
</li>

   
   
  
    <li class="nav-menu__item">
        <a href="{{ route('Contact') }}" class="nav-menu__link">Contact</a>
    </li>
</ul>

            <div class="d-sm-none d-block mt-24">
                <div class="header-select border border-neutral-30 bg-main-25 rounded-pill position-relative">
    <span class="select-icon d-xxl-block d-none position-absolute top-50 translate-middle-y inset-inline-start-0 z-1 ms-lg-4 ms-12 text-xl pointer-event-none d-flex">
        <i class="ph-bold ph-squares-four"></i>
    </span>
    <select class="js-example-basic-single border-0" name="state">
        <option value="1" selected disabled>Categories</option>
        <option value="1">Design</option>
        <option value="1">Development</option>
        <option value="1">Architecture</option>
        <option value="1">Life Style</option>
        <option value="1">Data Science</option>
        <option value="1">Marketing</option>
        <option value="1">Music</option>
        <option value="1">Typography</option>
        <option value="1">Finance</option>
        <option value="1">Motivation</option>
    </select>
</div>
            </div>
            
        </div>
    </div>
</div>
<!-- ==================== Mobile Menu End Here ==================== -->


    <!-- ==================== Header Start Here ==================== -->
<header class="header">
    <div class="container container--xl">
        <nav class="header-inner flex-between gap-8">

            <div class="header-content-wrapper flex-align flex-grow-1">
                <!-- Logo Start -->
                <div class="logo">
                    <a href="{{ route('home') }}" class="link">
<img src="{{ asset('assets/website/images/logo/logo.png') }}" alt="Logo" style="height: 80px;">
                    </a>
                </div>
                <!-- Logo End  -->
    
                <!-- Select Start -->
                <div class="d-sm-block d-none">
              @php
    // Change to App\Models\Category if that's your model name
    $cats = \App\Models\CourseCategory::orderBy('name')->get(['id','name','slug','name as title']);
@endphp

<div class="header-select border border-neutral-30 bg-main-25 rounded-pill position-relative">
    <span class="select-icon d-xxl-block d-none position-absolute top-50 translate-middle-y inset-inline-start-0 z-1 ms-lg-4 ms-12 text-xl pointer-event-none d-flex">
        <i class="ph-bold ph-squares-four"></i>
    </span>

    {{-- Navigate to CourseGrid when a category is selected (GET ?category=slug) --}}
    <form action="{{ route('CourseGrid') }}" method="GET" class="w-100">
        <select class="js-example-basic-single border-0 w-100"
                name="category"
                onchange="this.form.submit()">
            <option value="" selected disabled>Categories</option>
            @foreach($cats as $c)
                <option value="{{ $c->slug ?? $c->id }}">
                    {{ $c->name ?? $c->title }}
                </option>
            @endforeach
        </select>
    </form>
</div>

                </div>
                <!-- Select End -->
    
                <!-- Menu Start  -->
                <div class="header-menu d-lg-block d-none">
                    
<ul class="nav-menu flex-align ">
   <li class="nav-menu__item activePage">
    <a href="{{ route('home') }}" class="nav-menu__link">Home</a>
</li>
  <li class="nav-menu__item">
    <a href="{{ route('CourseGrid') }}" class="nav-menu__link">Courses</a>
</li>

  <li class="nav-menu__item">
    <a href="{{ route('about') }}" class="nav-menu__link">About</a>
</li>

   <li class="nav-menu__item">
    <a href="{{ route('cart.index') }}"
       class="nav-menu__link {{ request()->routeIs('cart.index') ? 'is-active' : '' }}">
        Cart
        @php $cartCount = count(session('cart', [])); @endphp
        @if($cartCount)
            <span class="badge bg-main-600 ms-2">{{ $cartCount }}</span>
        @endif
    </a>
</li>



   

    <li class="nav-menu__item">
        <a href="{{ route('Contact') }}" class="nav-menu__link">Contact</a>
    </li>
</ul>
                </div>
                <!-- Menu End  -->
            </div>

            <!-- Header Right start -->
            <div class="header-right flex-align">
                <form action="#" class="search-form position-relative d-xl-block d-none">
                    <input type="text" class="common-input rounded-pill bg-main-25 pe-48 border-neutral-30" placeholder="Search...">
                    <button type="submit" class="w-36 h-36 bg-main-600 hover-bg-main-700 rounded-circle flex-center text-md text-white position-absolute top-50 translate-middle-y inset-inline-end-0 me-8">
                        <i class="ph-bold ph-magnifying-glass"></i>
                    </button>
                </form>
                <a href="{{ route('login') }}" class="info-action w-52 h-52 bg-main-25 hover-bg-main-600 border border-neutral-30 rounded-circle flex-center text-2xl text-neutral-500 hover-text-white hover-border-main-600">
                    <i class="ph ph-user-circle"></i>
                </a>
                <button type="button" class="toggle-mobileMenu d-lg-none text-neutral-200 flex-center">
                    <i class="ph ph-list"></i> 
                </button>
            </div>
            <!-- Header Right End  -->
        </nav>
    </div>
</header>