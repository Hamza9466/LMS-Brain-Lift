@extends('admin.layouts.main')

@section('content')
@php
    // Helpers for display
    $finalPrice = $course->final_price;   // accessor
    $percentOff = $course->percent_off;   // accessor
    $isFree     = $course->is_free;

    $durationM  = $course->total_duration_minutes;
    $duration   = $durationM ? (floor($durationM/60).'h '.($durationM%60).'m') : '—';

    // Normalize arrays
    $learn       = $course->what_you_will_learn ?? [];
    $reqs        = $course->requirements ?? [];
    $audience    = $course->who_is_for ?? [];
    $tags        = $course->tags ?? [];
    $materials   = $course->materials ?? [];
    $breakdown   = $course->rating_breakdown ?? null; // e.g. {"5":75,"4":13,...}
@endphp

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Course Details</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <a href="{{ route('courses.index') }}" class="btn btn-sm btn-secondary">Back to Courses</a>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Header row: thumbnail + core meta + price --}}
                    <div class="row g-4 align-items-start">
                        <div class="col-md-3 text-center">
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" class="img-fluid rounded shadow-sm" alt="Thumbnail">
                            @else
                                <div class="border rounded p-4 text-muted">No thumbnail</div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <h3 class="mb-1">{{ $course->title }}</h3>

                            @if($course->short_description)
                                <p class="text-muted mb-2">{{ $course->short_description }}</p>
                            @endif

                            <div class="d-flex flex-wrap gap-2 mb-2">
                                @if($course->subject)
                                    <span class="badge bg-info-subtle text-dark border">Subject: {{ $course->subject }}</span>
                                @endif
                                <span class="badge bg-light text-dark border">Level: {{ $course->level }}</span>
                                <span class="badge bg-light text-dark border">Language: {{ $course->language ?? 'English' }}</span>
                                <span class="badge {{ $course->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                                @if($percentOff)
                                    <span class="badge bg-warning text-dark">{{ $percentOff }}% off</span>
                                @endif
                            </div>

                            <div class="small text-muted">
                                <div><strong>Category:</strong> {{ $course->category?->name ?? 'No category assigned' }}</div>
                                @if($course->published_at)
                                    <div><strong>Published at:</strong> {{ $course->published_at->format('d M Y, h:i A') }}</div>
                                @endif
                                <div><strong>Updated:</strong> {{ $course->updated_at->format('d M Y, h:i A') }}</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            {{-- Price card --}}
                            <div class="border rounded-3 p-3">
                                <div class="text-muted small mb-1">Pricing</div>

                                @if($isFree)
                                    <div class="h4 text-success mb-0">Free</div>
                                @else
                                    <div class="h4 mb-0">
                                        @if($finalPrice !== null)
                                            ${{ number_format($finalPrice, 2) }}
                                        @else
                                            —
                                        @endif
                                    </div>
                                    @if($course->compare_at_price)
                                        <div class="text-muted"><del>${{ number_format($course->compare_at_price, 2) }}</del></div>
                                    @elseif($course->price && $finalPrice !== null && $finalPrice < (float)$course->price)
                                        <div class="text-muted"><del>${{ number_format($course->price, 2) }}</del></div>
                                    @endif
                                @endif

                                <hr class="my-2">
                                <div class="small">
                                    <div><strong>Base price:</strong> {{ $course->price ? '$'.number_format($course->price,2) : '—' }}</div>
                                    <div><strong>Discount %:</strong> {{ $course->discount_percentage ?? '—' }}</div>
                                    <div><strong>Discount price:</strong> {{ $course->discount_price ? '$'.number_format($course->discount_price,2) : '—' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Quick stats --}}
                    <div class="row g-3">
                        <div class="col-sm-6 col-lg-3">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small">Lessons</div>
                                <div class="h5 mb-0">{{ $course->total_lessons ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small">Duration</div>
                                <div class="h5 mb-0">{{ $duration }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small">Enrolled</div>
                                <div class="h5 mb-0">{{ $course->enrollment_count ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small">Rating</div>
                                <div class="h5 mb-0">{{ number_format($course->rating_avg ?? 0,2) }} <small class="text-muted">/ 5</small></div>
                                <div class="small text-muted">{{ $course->rating_count ?? 0 }} ratings</div>
                            </div>
                        </div>
                    </div>

                    @if(is_array($breakdown) && count($breakdown))
                        <div class="mt-3">
                            <div class="text-muted small mb-1">Rating Breakdown</div>
                            <div class="row g-2">
                                @foreach([5,4,3,2,1] as $star)
                                    @php $pct = (int)($breakdown[(string)$star] ?? 0); @endphp
                                    <div class="col-12 d-flex align-items-center gap-2">
                                        <div style="width:40px">{{ $star }}★</div>
                                        <div class="flex-grow-1">
                                            <div class="progress" style="height:8px;">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div style="width:40px" class="text-end small text-muted">{{ $pct }}%</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <hr class="my-4">

                 
                    {{-- About / Long description --}}
                    @if($course->long_description)
                        <div class="mb-4">
                            <h6>About this course</h6>
                            <div class="text-body">{!! nl2br(e($course->long_description)) !!}</div>
                        </div>
                    @endif

                    {{-- What you'll learn --}}
                    @if(count($learn))
                        <div class="mb-4">
                            <h6>What you’ll learn</h6>
                            <ul class="mb-0">
                                @foreach($learn as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Requirements --}}
                    @if(count($reqs))
                        <div class="mb-4">
                            <h6>Requirements</h6>
                            <ul class="mb-0">
                                @foreach($reqs as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Who is this for --}}
                    @if(count($audience))
                        <div class="mb-4">
                            <h6>Target Audience</h6>
                            <ul class="mb-0">
                                @foreach($audience as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Tags / Materials --}}
                    <div class="row g-3">
                        @if(count($tags))
                            <div class="col-md-6">
                                <h6>Tags</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($tags as $t)
                                        <span class="badge bg-light text-dark border">{{ $t }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        @if(count($materials))
                            <div class="col-md-6">
                                <h6>Materials</h6>
                                <ul class="mb-0">
                                    @foreach($materials as $m)
                                        <li>{{ $m }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <hr class="my-4">

                    {{-- System info --}}
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small">Slug</div>
                                <div class="fw-medium">{{ $course->slug }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small">Created</div>
                                <div class="fw-medium">{{ $course->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small">Created By</div>
                                <div class="fw-medium">{{ $course->creator->name ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    @if($course->description)
                        <div class="mt-4">
                            <h6>Short Description (summary)</h6>
                            <div class="text-body">{{ $course->description }}</div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
