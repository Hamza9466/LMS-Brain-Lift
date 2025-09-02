@extends('website.layouts.main')

@section('content')
<div class="container py-4">
  <div class="row g-4">
    {{-- LEFT: Cart table --}}
    <div class="col-lg-8">
      <div class="d-flex align-items-end justify-content-between">
        <h2 class="mb-0">
          Cart
          <small class="text-muted">({{ count($cart) }} item{{ count($cart)===1?'':'s' }})</small>
        </h2>
        <a href="{{ url()->previous() }}" class="btn btn-link p-0">← Continue Shopping</a>
      </div>

      {{-- Notices --}}
      @php
        $firstKey      = function_exists('array_key_first') ? array_key_first($cart) : (count($cart) ? array_keys($cart)[0] : null);
        $firstCourseId = $firstKey !== null ? ($cart[$firstKey]['course_id'] ?? null) : null;
      @endphp

      @guest
        @if($firstCourseId)
          <div class="alert alert-info mt-3">
            Please <a href="{{ route('enroll.start', $firstCourseId) }}?from_cart=1#tab-login" class="alert-link">log in or register</a> to proceed to checkout.
          </div>
        @endif
      @endguest

      @if ($errors->any())
        <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
      @endif
      @if (session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
      @endif
      @if (session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
      @endif

      {{-- Helpers for image resolution --}}
      @php
        $placeholder = asset('assets/images/thumbs/course-img1.png');
        $resolveThumb = function ($path) use ($placeholder) {
            if (empty($path)) return $placeholder;

            if (filter_var($path, FILTER_VALIDATE_URL)) {
                return $path;
            }

            $p = ltrim($path, '/');

            if (str_starts_with($p, 'public/')) {
                $p = 'storage/' . substr($p, 7);
            }

            if (str_starts_with($p, 'storage/') || str_starts_with($p, 'assets/') || str_starts_with($p, 'uploads/')) {
                return asset($p);
            }

            return asset('storage/' . $p);
        };
      @endphp

      <div class="card shadow-sm mt-3">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead class="bg-light">
                <tr class="text-muted">
                  <th class="py-3 ps-3">Product</th>
                  <th class="py-3 text-center">Price</th>
                  <th class="py-3 text-center">Quantity</th>
                  <th class="py-3 text-end">Total Price</th>
                  <th class="py-3 text-end pe-3"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($cart as $row)
                  @php
                    $qty   = (int)($row['qty'] ?? 1);
                    $price = (float)($row['price'] ?? 0);
                    $line  = $price * $qty;

                    $thumbUrl = $resolveThumb($row['thumbnail'] ?? null);
                  @endphp
                  <tr>
                    <td class="ps-3">
                      <div class="d-flex align-items-center gap-3">
                        <div class="border rounded-3 overflow-hidden bg-white" style="width:60px;height:60px">
                          <img
                            src="{{ $thumbUrl }}"
                            alt="Item"
                            class="w-100 h-100"
                            style="object-fit:cover;"
                            onerror="this.onerror=null;this.src='{{ $placeholder }}';"
                          >
                        </div>
                        <div>
                          <div class="fw-semibold">{{ $row['title'] ?? 'Item' }}</div>
                          @if(!empty($row['slug']))
                            <div class="text-muted small">/{{ $row['slug'] }}</div>
                          @endif
                        </div>
                      </div>
                    </td>

                    <td class="text-center">
                      {{ number_format($price, 2) }} {{ env('APP_CURRENCY','USD') }}
                    </td>

                    <td class="text-center">
                      <div class="d-inline-flex align-items-center gap-2">
                        {{-- minus --}}
                        <form method="POST" action="{{ route('cart.remove') }}">
                          @csrf
                          <input type="hidden" name="course_id" value="{{ $row['course_id'] }}">
                          <input type="hidden" name="decrement" value="1">
                          <button class="btn btn-outline-primary btn-sm" {{ $qty<=1 ? 'disabled' : '' }}>−</button>
                        </form>

                        <span class="fw-semibold mx-2">{{ $qty }}</span>

                        {{-- plus --}}
                        <form method="POST" action="{{ route('cart.add', $row['course_id']) }}">
                          @csrf
                          <input type="hidden" name="qty" value="1">
                          <button class="btn btn-outline-primary btn-sm">+</button>
                        </form>
                      </div>
                    </td>

                    <td class="text-end">
                      {{ number_format($line, 2) }} {{ env('APP_CURRENCY','USD') }}
                    </td>

                    <td class="text-end pe-3">
                      <form method="POST" action="{{ route('cart.remove') }}" onsubmit="return confirm('Remove this item?')">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $row['course_id'] }}">
                        <button class="btn btn-sm btn-outline-danger">
                          <span class="d-inline-block me-1">&#128465;</span> Remove
                        </button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-5">Your cart is empty.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    {{-- RIGHT: Summary --}}
    <div class="col-lg-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="text-muted">From</span>
            <span class="fs-3 fw-bold">${{ number_format(max($total,0),2) }}</span>
          </div>
          <hr>

          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
              <span class="text-muted">Base Price</span>
              <span>{{ number_format($subtotal,2) }} {{ env('APP_CURRENCY','USD') }}</span>
            </li>

            <li class="list-group-item px-0">
              <form method="POST" action="{{ route('cart.coupon') }}" class="mt-2">
                @csrf
                <div class="input-group">
                  <input type="text" name="code" class="form-control" placeholder="DISCOUNT9"
                         value="{{ $coupon['code'] ?? '' }}" {{ count($cart)?'':'disabled' }}>
                  <button class="btn btn-primary" type="submit" {{ count($cart)?'':'disabled' }}>
                    <span>&rarr;</span>
                  </button>
                </div>
              </form>
            </li>

            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
              <span class="text-muted">
                Discount @if(!empty($coupon['code'])) ({{ $coupon['code'] }}) @endif
              </span>
              <span class="text-danger">-{{ number_format($discount,2) }} {{ env('APP_CURRENCY','USD') }}</span>
            </li>

            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
              <span class="fw-semibold">Total</span>
              <span class="fw-semibold text-primary">{{ number_format($total,2) }} {{ env('APP_CURRENCY','USD') }}</span>
            </li>
          </ul>

          {{-- CTA: Guest -> Enroll; Auth -> Checkout --}}
          <div class="d-grid mt-3">
            @if(count($cart))
              @auth
                <a href="{{ route('enroll.start', $firstCourseId) }}" class="btn btn-primary btn-lg rounded-pill">
                  Check Out
                </a>
              @else
                @if($firstCourseId)
                  <a href="{{ route('enroll.start', $firstCourseId) }}?from_cart=1#tab-login" class="btn btn-primary btn-lg rounded-pill">
                    Login / Register to Checkout
                  </a>
                @else
                  <button class="btn btn-primary btn-lg rounded-pill" disabled>Check Out</button>
                @endif
              @endauth
            @else
              <button class="btn btn-primary btn-lg rounded-pill" disabled>Check Out</button>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
