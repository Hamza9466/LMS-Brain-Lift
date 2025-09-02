{{-- resources/views/admin/pages/cart/checkout.blade.php --}}
@extends('admin.layouts.main')

@section('content')
@php
    use App\Models\Course;
    use App\Models\PersonalDiscount;
    use Illuminate\Support\Facades\Schema;

    $currency = env('APP_CURRENCY','USD');
    $cart     = $cart ?? session('cart', []);

    $subtotal              = 0.0;
    $personalDiscountTotal = 0.0;

    $userId = auth()->id();
    $now    = now();

    foreach ($cart as $row) {
        $qty       = (int)($row['qty'] ?? 1);
        $unitPrice = (float)($row['price'] ?? 0);
        $subtotal += $unitPrice * $qty;

        if ($userId) {
            $course = Course::find($row['course_id'] ?? null);
            if ($course) {
                $pd = PersonalDiscount::where('course_id', $course->id)
                    ->where('user_id', $userId)
                    ->when(Schema::hasColumn('personal_discounts','active'), fn($q)=>$q->where('active', true))
                    ->where(fn($q)=>$q->whereNull('starts_at')->orWhere('starts_at','<=',$now))
                    ->where(fn($q)=>$q->whereNull('ends_at')->orWhere('ends_at','>=',$now))
                    ->where(fn($q)=>$q->whereNull('max_uses')->orWhereColumn('uses','<','max_uses'))
                    ->orderByDesc('value')
                    ->first();

                $unitPersonal = 0.0;
                if ($pd) {
                    if (Schema::hasColumn('personal_discounts','type') && Schema::hasColumn('personal_discounts','value')) {
                        $unitPersonal = $pd->type === 'percent'
                            ? round($unitPrice * ((float)$pd->value / 100), 2)
                            : (float) min($unitPrice, (float)$pd->value);
                    } elseif (Schema::hasColumn('personal_discounts','percentage')) {
                        $unitPersonal = round($unitPrice * ((float)$pd->percentage / 100), 2);
                    }
                }

                $personalDiscountTotal += $unitPersonal * $qty;
            }
        }
    }

    $coupon         = session('coupon');
    $couponDiscount = (float)($coupon['amount'] ?? 0);
    $discount       = $personalDiscountTotal + $couponDiscount;
    $total          = max($subtotal - $discount, 0);

    $placeholder = asset('assets/images/thumbs/course-img1.png');
    $resolveThumb = function ($path) use ($placeholder) {
        if (empty($path)) return $placeholder;
        if (filter_var($path, FILTER_VALIDATE_URL)) return $path;
        $p = ltrim($path, '/');
        if (str_starts_with($p, 'public/')) $p = 'storage/' . substr($p, 7);
        if (str_starts_with($p, 'storage/') || str_starts_with($p, 'assets/') || str_starts_with($p, 'uploads/')) {
            return asset($p);
        }
        return asset('storage/' . $p);
    };
@endphp

<div class="container py-4">
  {{-- Breadcrumb --}}
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
      <li class="breadcrumb-item active" aria-current="page">Checkout</li>
    </ol>
  </nav>

  <div class="d-flex align-items-center justify-content-between">
    <h2 class="mb-0 fw-bold">Checkout</h2>
    <a href="{{ route('cart.index') }}" class="btn btn-link">← Back to Cart</a>
  </div>

  {{-- Flash + Errors --}}
  @if ($errors->any())
    <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
  @endif
  @if (session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
  @endif
  @if (session('error'))
    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
  @endif

  <div class="row g-4 mt-1">
    {{-- LEFT: Order Summary --}}
    <div class="col-lg-8">
      <div class="card border-0 shadow">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h5 class="mb-0 fw-semibold">Order Summary</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr class="text-muted">
                  <th>Course</th>
                  <th class="text-center" style="width:120px;">Qty</th>
                  <th class="text-end" style="width:180px;">Price</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                @forelse($cart as $row)
                  @php
                    $qty       = (int)($row['qty'] ?? 1);
                    $unitPrice = (float)($row['price'] ?? 0);
                    $thumbUrl  = $resolveThumb($row['thumbnail'] ?? null);
                  @endphp
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <img src="{{ $thumbUrl }}" alt="Course" width="56" height="56"
                             class="rounded border img-fluid"
                             loading="lazy"
                             onerror="this.onerror=null;this.src='{{ $placeholder }}';">
                        <div class="min-w-0">
                          <div class="fw-semibold text-truncate">{{ $row['title'] ?? 'Course' }}</div>
                          @if(!empty($row['slug']))
                            <div class="small text-muted text-truncate">/{{ $row['slug'] }}</div>
                          @endif
                        </div>
                      </div>
                    </td>
                    <td class="text-center">{{ $qty }}</td>
                    <td class="text-end">{{ number_format($unitPrice * $qty, 2) }} {{ $currency }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center text-muted py-4">Your cart is empty.</td>
                  </tr>
                @endforelse
              </tbody>
              <tfoot class="table-group-divider">
                <tr>
                  <th class="text-muted fw-normal">Subtotal</th>
                  <th></th>
                  <th class="text-end fw-semibold">{{ number_format($subtotal,2) }} {{ $currency }}</th>
                </tr>
                <tr>
                  <th class="text-muted fw-normal">Discount <span class="small text-muted">(Personal)</span></th>
                  <th></th>
                  <th class="text-end text-success fw-semibold">-{{ number_format($personalDiscountTotal,2) }} {{ $currency }}</th>
                </tr>
                @if($couponDiscount > 0)
                <tr>
                  <th class="text-muted fw-normal">
                    Discount <span class="small text-muted">(Coupon{{ !empty($coupon['code']) ? ': '.$coupon['code'] : '' }})</span>
                  </th>
                  <th></th>
                  <th class="text-end text-success fw-semibold">-{{ number_format($couponDiscount,2) }} {{ $currency }}</th>
                </tr>
                @endif
                <tr class="table-light">
                  <th class="fs-5">Total</th>
                  <th></th>
                  <th class="text-end fs-4 fw-bold">{{ number_format($total,2) }} {{ $currency }}</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>

      <div class="d-grid d-lg-none mt-3">
        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">← Back to Cart</a>
      </div>
    </div>

    {{-- RIGHT: Coupon + Payment (no duplicate summary) --}}
    <div class="col-lg-4">
      {{-- Coupon --}}
      <div class="card border-0 shadow mb-3">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h6 class="mb-0 fw-semibold">Have a Coupon?</h6>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('cart.coupon') }}">
            @csrf
            <div class="input-group">
              <input type="text" name="code" class="form-control" placeholder="COUPONCODE"
                     value="{{ $coupon['code'] ?? '' }}" {{ count($cart)?'':'disabled' }}>
              <button class="btn btn-primary" type="submit" {{ count($cart)?'':'disabled' }}>Apply</button>
            </div>
            @if(!empty($coupon['code']))
              <div class="small text-muted mt-2">
                Applied coupon:
                <span class="badge text-bg-primary">{{ $coupon['code'] }}</span>
              </div>
            @endif
          </form>
        </div>
      </div>

      {{-- Payment --}}
      <div class="card border-0 shadow">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h6 class="mb-0 fw-semibold">Payment</h6>
        </div>
        <div class="card-body">
          @auth
            <form method="POST" action="{{ route('checkout.fake') }}">
              @csrf
             <div class="mb-3">
  <label for="gateway" class="form-label">Select Payment Method</label>
  <select id="gateway" name="gateway" class="form-select" required>
    <option value="">-- Choose a method --</option>
    <option value="jazzcash"  {{ old('gateway')==='jazzcash'  ? 'selected' : '' }}>JazzCash</option>
    <option value="easypaisa" {{ old('gateway')==='easypaisa' ? 'selected' : '' }}>EasyPaisa</option>
    <option value="bank"      {{ old('gateway')==='bank'      ? 'selected' : '' }}>Bank Account</option>
  </select>
</div>

              <div class="alert alert-secondary d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Amount to pay</span>
                <span class="fw-semibold">{{ number_format($total,2) }} {{ $currency }}</span>
              </div>

              <button class="btn btn-success btn-lg w-100" {{ count($cart)?'':'disabled' }}>Buy Now</button>
            </form>
          @endauth

          @guest
            <div class="alert alert-info mb-0">
              Please <a href="{{ route('login', ['intended' => route('cart.checkout')]) }}">log in</a>
              to complete your purchase.
            </div>
          @endguest

          <p class="small text-muted mt-3 mb-0">
            By placing your order you agree to our <a class="link-secondary" href="#">Terms</a> &amp;
            <a class="link-secondary" href="#">Refund Policy</a>.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
