@extends('website.layouts.main')

@section('content')
@php
    // ---------- Computed helpers ----------
    $thumb = $course->thumbnail
        ? (preg_match('~^https?://~', $course->thumbnail) ? $course->thumbnail : asset('storage/'.ltrim($course->thumbnail,'/')))
        : asset('assets/images/thumbs/course-details-img.png');

    $mins     = (int)($course->total_duration_minutes ?? 0);
    $duration = $mins ? floor($mins/60).' Weeks' : null; // keep your sidebar "7 Weeks" look or change to hours/mins

    // lessons (prefer cached column; else sum lessons from sections)
    $lessonsCount = (int)($course->total_lessons
        ?? optional($course->sections)->sum(fn($s) => optional($s->lessons)->count() ?? 0) ?? 0);

    // price label
    if ($course->is_free) {
        $priceLabel = 'Free';
        $compareTo  = null;
    } else {
        $base  = $course->price;
        $final = $course->discount_price
            ?? (($course->price !== null && $course->discount_percentage !== null)
                ? round($course->price - ($course->price * $course->discount_percentage / 100), 2)
                : $course->price);

        $priceLabel = $final !== null ? '$'.number_format($final, 2) : '—';
        $compareTo  = $course->compare_at_price ?: (($base && $final && $final < $base) ? $base : null);
    }

    // rating (if you store these)
    $ratingAvg   = $course->rating_avg ?? null;
    $ratingCount = $course->rating_count ?? null;

    // arrays (JSON columns)
    $learn     = is_array($course->what_you_will_learn) ? $course->what_you_will_learn : [];
    $reqs      = is_array($course->requirements) ? $course->requirements : [];
    $audience  = is_array($course->who_is_for) ? $course->who_is_for : [];
@endphp

<!-- ==================== Breadcrumb ==================== -->
<section class="breadcrumb py-120 bg-main-25 position-relative z-1 overflow-hidden mb-0">
    <img src="{{ asset('assets/images/shapes/shape1.png') }}" alt="" class="shape one animation-rotation d-md-block d-none">
    <img src="{{ asset('assets/images/shapes/shape2.png') }}" alt="" class="shape two animation-scalation d-md-block d-none">
    <img src="{{ asset('assets/images/shapes/shape3.png') }}" alt="" class="shape eight animation-walking d-md-block d-none">
    <img src="{{ asset('assets/images/shapes/shape5.png') }}" alt="" class="shape six animation-walking d-md-block d-none">
    <img src="{{ asset('assets/images/shapes/shape4.png') }}" alt="" class="shape four animation-scalation">
    <img src="{{ asset('assets/images/shapes/shape4.png') }}" alt="" class="shape nine animation-scalation">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="breadcrumb__wrapper">
                    <h1 class="breadcrumb__title display-4 fw-semibold text-center">{{ $course->title }}</h1>
                    <ul class="breadcrumb__list d-flex align-items-center justify-content-center gap-4">
                        <li class="breadcrumb__item">
                            <a href="{{ route('home') }}" class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium">
                                <i class="text-lg d-inline-flex ph-bold ph-house"></i> Home
                            </a>
                        </li>
                        <li class="breadcrumb__item"><i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i></li>
                        <li class="breadcrumb__item">
                            <a href="{{ route('CourseGrid') }}" class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium">Courses</a>
                        </li>
                        <li class="breadcrumb__item"><i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i></li>
                        <li class="breadcrumb__item"><span class="text-main-two-600">{{ $course->title }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== Course Details ==================== -->
<section class="course-details py-120">
    <div class="container">
        <div class="row gy-4">
            <!-- Main -->
            <div class="col-xl-8">
                <div class="course-details__content border border-neutral-30 rounded-12 bg-main-25 p-12">
                    <img src="{{ $thumb }}" alt="{{ $course->title }}" class="rounded-8 cover-img">
                    <div class="p-20">
                        <h2 class="mt-24 mb-24">{{ $course->title }}</h2>

                        @if($course->short_description)
                            <p class="text-neutral-700">{{ $course->short_description }}</p>
                        @endif

                        @if($course->long_description)
                            <span class="d-block border-bottom border-main-100 my-32"></span>
                            <div class="text-neutral-700">{!! nl2br(e($course->long_description)) !!}</div>
                        @endif

                        @if(count($learn))
                            <span class="d-block border-bottom border-main-100 my-32"></span>
                            <h3 class="mb-16">What You Will Learn</h3>
                            <ul class="list-dotted d-flex flex-column gap-24">
                                @foreach($learn as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @endif

                        @if(count($reqs))
                            <span class="d-block border-bottom border-main-100 my-32"></span>
                            <h4 class="mb-16">Requirements</h4>
                            <ul class="list-dotted d-flex flex-column gap-24">
                                @foreach($reqs as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @endif

                        @if(count($audience))
                            <span class="d-block border-bottom border-main-100 my-32"></span>
                            <h5 class="mb-16">Ideal For</h5>
                            <ul class="list-dotted d-flex flex-column gap-24">
                                @foreach($audience as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- Curriculum -->
                <div class="border border-neutral-30 rounded-12 bg-main-25 p-32 mt-24">
                    <h5 class="mb-0">Curriculum</h5>
                    <span class="d-block border border-neutral-30 my-24 border-dashed"></span>

                    @if($course->relationLoaded('sections') && $course->sections && $course->sections->count())
                        <div class="accordion common-accordion style-three" id="courseCurriculum">
                            @foreach($course->sections as $sIdx => $section)
                                @php
                                    $open     = $sIdx === 0 ? 'show' : '';
                                    $expanded = $sIdx === 0 ? 'true' : 'false';
                                    $secId    = 'sec-'.$course->id.'-'.$sIdx;
                                @endphp
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button {{ $open ? '' : 'collapsed' }}" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#{{ $secId }}"
                                                aria-expanded="{{ $expanded }}" aria-controls="{{ $secId }}">
                                            {{ $section->title ?? ('Section '.($sIdx+1)) }}
                                        </button>
                                    </h2>
                                    <div id="{{ $secId }}" class="accordion-collapse collapse {{ $open }}" data-bs-parent="#courseCurriculum">
                                        <div class="accordion-body p-0">
                                            @forelse($section->lessons ?? [] as $lesson)
                                                @php
                                                    $lmin = (int)($lesson->duration_minutes ?? 0);
                                                    $ldur = $lmin ? sprintf('%02d:%02d', floor($lmin/60), $lmin%60) : null;
                                                @endphp
                                                <span class="curriculam-item flex-between gap-16 text-neutral-500 fw-medium">
                                                    <span class="flex-align gap-12">
                                                        <i class="text-xl d-flex ph-bold ph-video-camera"></i>
                                                        <span class="text-line-1">{{ $lesson->title ?? 'Lesson' }}</span>
                                                    </span>
                                                    <span class="flex-align gap-12 flex-shrink-0">
                                                        {{ $ldur ?? '' }}
                                                        <i class="text-xl d-flex ph-bold ph-video-camera"></i>
                                                    </span>
                                                </span>
                                            @empty
                                                <div class="p-16 text-neutral-500">No lessons added yet.</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-neutral-500">Curriculum will be available soon.</div>
                    @endif
                </div>

                <!-- (Optional) FAQs / Reviews content left as-is or wire to DB if you have tables -->
                {{-- Keep your existing FAQs/Reviews markup here if desired --}}
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4">
                <div class="course-details__sidebar border border-neutral-30 rounded-12 bg-white p-8">
                    <div class="border border-neutral-30 rounded-12 bg-main-25 p-24">
                        <span class="text-neutral-700 text-lg mb-12">Price</span>

                        <div class="flex-align align-items-start flex-wrap gap-8 border-bottom border-neutral-40 pb-24 mb-24">
                            <div class="flex-align gap-12 text-neutral-700">
                                <span class="text-2xl d-flex"><i class="ph-bold ph-tag"></i></span>
                                <span>From</span>
                                <h2 class="mb-0">
                                    @if($course->is_free)
                                        Free
                                    @else
                                        {{ $priceLabel }}
                                        @if($compareTo)
                                            <small class="text-decoration-line-through text-neutral-500 ms-2">${{ number_format($compareTo, 2) }}</small>
                                        @endif
                                    @endif
                                </h2>
                            </div>
                        </div>

                        <div class="border-bottom border-neutral-40 pb-24 mb-24 flex-between flex-wrap gap-16">
                            <div class="flex-align gap-12">
                                <span class="text-neutral-700 text-2xl d-flex"><i class="ph ph-watch"></i></span>
                                <span class="text-neutral-700 text-lg fw-normal">Course Title</span>
                            </div>
                            <span class="text-lg fw-medium text-neutral-700">{{ $course->title }}</span>
                        </div>

                        <div class="border-bottom border-neutral-40 pb-24 mb-24 flex-between flex-wrap gap-16">
                            <div class="flex-align gap-12">
                                <span class="text-neutral-700 text-2xl d-flex"><i class="ph ph-video-camera"></i></span>
                                <span class="text-neutral-700 text-lg fw-normal">Lessons</span>
                            </div>
                            <span class="text-lg fw-medium text-neutral-700">{{ $lessonsCount }} {{ Str::plural('Video', $lessonsCount) }}</span>
                        </div>

                        <div class="border-bottom border-neutral-40 pb-24 mb-24 flex-between flex-wrap gap-16">
                            <div class="flex-align gap-12">
                                <span class="text-neutral-700 text-2xl d-flex"><i class="ph ph-globe"></i></span>
                                <span class="text-neutral-700 text-lg fw-normal">Language</span>
                            </div>
                            <span class="text-lg fw-medium text-neutral-700">{{ $course->language ?? 'English' }}</span>
                        </div>

                        <div class="border-bottom border-neutral-40 pb-24 mb-24 flex-between flex-wrap gap-16">
                            <div class="flex-align gap-12">
                                <span class="text-neutral-700 text-2xl d-flex"><i class="ph ph-chart-pie"></i></span>
                                <span class="text-neutral-700 text-lg fw-normal">Course Level</span>
                            </div>
                            <span class="text-lg fw-medium text-neutral-700">{{ $course->level ?? 'Beginner' }}</span>
                        </div>

                        @if($ratingAvg !== null)
                        <div class="border-bottom border-neutral-40 pb-24 mb-24 flex-between flex-wrap gap-16">
                            <div class="flex-align gap-12">
                                <span class="text-neutral-700 text-2xl d-flex"><i class="ph ph-star"></i></span>
                                <span class="text-neutral-700 text-lg fw-normal">Reviews</span>
                            </div>
                            <span class="text-lg fw-medium text-neutral-700">
                                {{ number_format((float)$ratingAvg, 1) }}{{ $ratingCount ? '('.number_format($ratingCount).')' : '' }}
                            </span>
                        </div>
                        @endif

                        @if($mins)
                        <div class="border-bottom border-neutral-40 pb-24 mb-24 flex-between flex-wrap gap-16">
                            <div class="flex-align gap-12">
                                <span class="text-neutral-700 text-2xl d-flex"><i class="ph ph-clock"></i></span>
                                <span class="text-neutral-700 text-lg fw-normal">Duration</span>
                            </div>
                            <span class="text-lg fw-medium text-neutral-700">{{ $duration }}</span>
                        </div>
                        @endif

                        <div class="border-bottom border-neutral-40 pb-24 mb-24 flex-between flex-wrap gap-16">
                            <div class="flex-align gap-12">
                                <span class="text-neutral-700 text-2xl d-flex"><i class="ph ph-users"></i></span>
                                <span class="text-neutral-700 text-lg fw-normal">Students</span>
                            </div>
                            <span class="text-lg fw-medium text-neutral-700">{{ number_format($course->enrollment_count ?? 0) }}</span>
                        </div>

                        <div class="border-bottom border-neutral-40 pb-24 mb-24 flex-between flex-wrap gap-16">
                            <div class="flex-align gap-12">
                                <span class="text-neutral-700 text-2xl d-flex"><i class="ph ph-calendar-dot"></i></span>
                                <span class="text-neutral-700 text-lg fw-normal">Published</span>
                            </div>
                            <span class="text-lg fw-medium text-neutral-700">
                                {{ optional($course->published_at)->format('d M, Y') ?? '—' }}
                            </span>
                        </div>

                      

                        <a href="{{ route('CourseGrid') }}" class="btn btn-main rounded-pill flex-center gap-8 mt-40">
                            Browse More Courses
                            <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                        </a>

                        {{-- Share/Cart buttons kept as in your template --}}
     @php
  $cart = session('cart', []);
  $inCart = array_key_exists($course->id, $cart);
@endphp

<div class="mt-24 flex-center gap-24">
  @if ($inCart)
    <button type="button"
            class="btn btn-secondary rounded-pill w-100 d-inline-flex align-items-center justify-content-center gap-8 bg-warning"
            disabled>
      <i class="ph-bold ph-check-circle text-xl" aria-hidden="true"></i>
      <span>Already in Cart</span>
    </button>
  @else
    {{-- Add to Cart for everyone (no login required) --}}
    <form method="POST" action="{{ route('cart.add', ['course' => $course->id]) }}" class="w-100">
      @csrf
      <input type="hidden" name="qty" value="1">
      <button type="submit"
              class="btn btn-outline-primary rounded-pill w-100 d-inline-flex align-items-center justify-content-center gap-8 bg-warning"
              title="Add to Cart">
        <i class="ph-bold ph-shopping-cart-simple text-xl" aria-hidden="true"></i>
        <span>Add to Cart</span>
      </button>
    </form>
  @endif
</div>



<!-- /buttons -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- review --}}

@php
    // Tiny helper to draw 5 stars
    $renderStars = function (int $rating): string {
        $r = max(0, min(5, (int) $rating));
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            $icon = $i <= $r ? 'ph-fill ph-star' : 'ph ph-star';
            $html .= '<span class="text-xl fw-medium text-warning-600 d-flex"><i class="'.$icon.'"></i></span>';
        }
        return $html;
    };
@endphp

@forelse ($approvedReviews as $review)
    @php
        // Alternate backgrounds: odd = light gray, even = white
        $bg = $loop->odd ? '#F7F9FC' : '#FFFFFF';
    @endphp

    <div class="border border-neutral-30 rounded-12 p-32 mt-24" style="background-color: {{ $bg }};">
        {{-- Header: stars on left, index badge on right --}}
        <div class="d-flex align-items-center justify-content-between mb-16">
            <div class="flex-align gap-8">
                {!! $renderStars((int) $review->rating) !!}
            </div>

            <span
                class="d-inline-flex align-items-center justify-content-center rounded-circle"
                style="width:32px;height:32px;background:#E9F5FF;color:#0B6BCB;font-weight:700;">
                {{ $loop->iteration }}
            </span>
        </div>

        @if(!empty($review->comment))
            <p class="text-neutral-700 mb-0">{{ $review->comment }}</p>
        @elseif(!empty($review->title))
            <p class="text-neutral-700 mb-0">{{ $review->title }}</p>
        @else
            <p class="text-neutral-700 mb-0">No comment provided.</p>
        @endif

        <span class="d-block border border-neutral-30 my-24 border-dashed"></span>

        <div>
            <h6 class="text-xl mb-4 fw-medium">
                {{ $review->user?->full_name ?? 'Anonymous' }}
            </h6>
            <span class="text-neutral-700 text-sm">
                {{ $review->created_at->diffForHumans() }}
            </span>
            @if(!empty($review->title))
                <div class="text-neutral-600 text-sm mt-4">{{ $review->title }}</div>
            @endif
        </div>

        <span class="d-block border border-neutral-30 my-24 border-dashed"></span>

        <div class="flex-align flex-wrap gap-40">
            <button type="button" class="like-button flex-align gap-8 text-neutral-500 hover-text-main-600">
                <span class="like-button__icon text-xl d-flex">
                    <i class="ph-bold ph-thumbs-up"></i>
                </span>
                <span class="like-button__text">0</span>
            </button>
            <a href="#commentForm" class="flex-align gap-8 text-neutral-500 hover-text-main-600">
                <i class="text-xl d-flex ph-bold ph-chat-centered-text"></i>
                Reply
            </a>
        </div>
    </div>
@empty
    <div class="border border-neutral-30 rounded-12 p-32 mt-24" style="background-color:#F7F9FC;">
        <p class="text-neutral-700 mb-0">No reviews yet.</p>
    </div>
@endforelse




{{-- Certificate CTA kept as-is --}}
<div class="certificate">
    <div class="container container--lg">
        <div class="certificate-box px-16 bg-main-600 rounded-16">
            <div class="container">
                <div class="position-relative py-80">
                    <div class="row align-items-center">
                        <div class="col-xl-6">
                            <div class="certificate__content">
                                <div class="flex-align gap-8 mb-16">
                                    <span class="w-8 h-8 bg-white rounded-circle"></span>
                                    <h5 class="text-white mb-0">Get Certificate</h5>
                                </div>
                                <h2 class="text-white mb-40 fw-medium">Get Quality Skills Certificate From the Brain Lift</h2>
                                <a href="{{ route('CourseGrid') }}" class="btn btn-white rounded-pill flex-align d-inline-flex gap-8">
                                    Get Started Now
                                    <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-6 d-xl-block d-none">
                            <div class="certificate__thumb">
                                <img src="{{ asset('assets/images/thumbs/certificate-img.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>



  
@endsection
