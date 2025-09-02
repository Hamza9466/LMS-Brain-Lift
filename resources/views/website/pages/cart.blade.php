@extends('website.layouts.main')

@section('content')
<!-- ==================== Breadcrumb Start Here ==================== -->
{{-- <section class="breadcrumb py-120 bg-main-25 position-relative z-1 overflow-hidden mb-0">
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
                    <h1 class="breadcrumb__title display-4 fw-semibold text-center">Cart List</h1>
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
                        <li class="breadcrumb__item"><span class="text-main-two-600">Cart List</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ==================== Breadcrumb End Here ==================== -->

<!-- =============================== Cart Section Start ================================== -->
<div class="py-120">
    <div class="container">
        <div class="row">
            <!-- Cart Table -->
            <div class="col-lg-8">
                <div class="border border-neutral-30 rounded-12 bg-main-25 p-32 bg-main-25">
                    {{-- Alerts --}}
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <h4 class="mb-0">
                        Cart
                        <span class="text-neutral-100 fw-normal">({{ count($cart) }} item{{ count($cart) === 1 ? '' : 's' }})</span>
                    </h4>

                    <span class="d-block border border-neutral-30 my-24 border-dashed"></span>

                    <div class="table-responsive overflow-x-auto">
                        <table class="table min-w-max vertical-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-neutral-500 fw-semibold px-24 py-20 border-0">Product</th>
                                    <th class="text-neutral-500 fw-semibold px-24 py-20 border-0">Price</th>
                                    <th class="text-neutral-500 fw-semibold px-24 py-20 border-0">Quantity</th>
                                    <th class="text-neutral-500 fw-semibold px-24 py-20 border-0">Total Price</th>
                                    <th class="text-neutral-500 fw-semibold px-24 py-20 border-0"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cart as $item)
                                    @php
                                        $qty = (int)($item['qty'] ?? 1);
                                        $price = (float)($item['price'] ?? 0);
                                        $lineTotal = $price * $qty;

                                        $thumb = $item['thumbnail'] ?? null;
                                        if (!$thumb) {
                                            $thumb = asset('assets/images/thumbs/course-img1.png');
                                        } else {
                                            $thumb = preg_match('~^https?://~', $thumb)
                                                ? $thumb
                                                : (str_starts_with($thumb, 'storage/') || str_starts_with($thumb, 'assets/')
                                                    ? asset($thumb)
                                                    : asset('storage/'.ltrim($thumb,'/')));
                                        }
                                    @endphp
                                    <tr>
                                        <td class="border-bottom border-dashed border-neutral-40 text-neutral-700 bg-transparent px-24 py-20">
                                            <div class="d-flex align-items-center gap-24">
                                                <div class="w-60 h-60 border border-neutral-40 rounded-8 d-flex justify-content-center align-items-center bg-white overflow-hidden">
                                                    <img src="{{ $thumb }}" alt="Course" class="w-100 h-100 object-fit-cover">
                                                </div>
                                                <div>
                                                    <h6 class="text-md mb-8 text-line-1">{{ $item['title'] ?? 'Course' }}</h6>
                                                    @if (!empty($item['slug']))
                                                        <a href="{{ route('CourseDetail', $item['slug']) }}" class="text-sm text-main-600">View details</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <td class="border-bottom border-dashed border-neutral-40 text-neutral-700 bg-transparent px-24 py-20">
                                            ${{ number_format($price, 2) }}
                                        </td>

                                        <td class="border-bottom border-dashed border-neutral-40 text-neutral-700 bg-transparent px-24 py-20">
                                            {{-- Static display; add an update route if you want live edit --}}
                                            <div class="border border-neutral-40 bg-main-50 rounded-pill p-4 max-w-116 w-100 d-flex justify-content-between">
                                                <span class="flex-grow-1 text-center text-lg fw-semibold">{{ $qty }}</span>
                                            </div>
                                        </td>

                                        <td class="border-bottom border-dashed border-neutral-40 text-neutral-700 bg-transparent px-24 py-20">
                                            ${{ number_format($lineTotal, 2) }}
                                        </td>

                                        <td class="border-bottom border-dashed border-neutral-40 text-neutral-700 bg-transparent px-24 py-20">
                                            <form method="POST" action="{{ route('cart.remove') }}">
                                                @csrf
                                                <input type="hidden" name="course_id" value="{{ $item['course_id'] }}">
                                                <button class="delete-btn text-lg hover-text-main-600 transition-2" title="Remove">
                                                    <i class="ph ph-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-24 py-32 text-center text-muted">
                                            Your cart is empty.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <a href="{{ route('CourseGrid') }}" class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold mt-24" tabindex="0">
                            <i class="ph ph-arrow-left"></i>
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>

            <!-- Summary Sidebar -->
            <div class="col-lg-4">
                <div class="course-details__sidebar border border-neutral-30 rounded-12 bg-white p-8">
                    <div class="border border-neutral-30 rounded-12 bg-main-25 p-24">
                        <span class="text-neutral-700 text-lg mb-12">Price</span>

                        <div class="d-flex flex-column gap-24">
                            <div class="d-flex align-items-center justify-content-between gap-4">
                                <span class="text-neutral-500">Subtotal</span>
                                <span class="text-neutral-700 fw-medium">${{ number_format($subtotal, 2) }}</span>
                            </div>

                          <form method="POST" action="{{ route('cart.coupon') }}" class="my-0 position-relative">
    @csrf
    <input type="text" name="code" class="form-control bg-white shadow-none border border-neutral-30 rounded-pill h-48 ps-24 pe-44 focus-border-main-600"
           placeholder="COUPONCODE" value="{{ $coupon['code'] ?? '' }}">
    <button type="submit" class="w-36 h-36 flex-center rounded-circle bg-main-600 text-white hover-bg-main-800 position-absolute top-50 translate-middle-y inset-inline-end-0 me-8">
        <i class="ph ph-arrow-right"></i>
    </button>
</form>

                            <div class="d-flex align-items-center justify-content-between gap-4">
                                <span class="text-neutral-500">Discount</span>
                                <span class="text-main-two-600 fw-medium">- ${{ number_format($discount, 2) }}</span>
                            </div>

                            <span class="d-block border border-neutral-30 my-8 border-dashed"></span>

                            <div class="d-flex align-items-center justify-content-between gap-4">
                                <span class="text-neutral-500">Total</span>
                                <span class="text-main-600 fw-medium">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <a href="{{ route('cart.checkout') }}" class="btn btn-main rounded-pill w-100 mt-32 {{ count($cart) ? '' : 'disabled' }}">
                            Check Out
                        </a>
                    </div>
                </div>
            </div>
            <!-- /Summary Sidebar -->
        </div>
    </div>
</div> --}}
<!-- =============================== Cart Section End ================================== -->
@endsection
