{{-- resources/views/admin/pages/cart/checkout-proof.blade.php --}}
@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
  {{-- Breadcrumb --}}
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
      <li class="breadcrumb-item active" aria-current="page">Upload Payment Proof</li>
    </ol>
  </nav>

  {{-- Page header --}}
  <div class="d-flex align-items-center justify-content-between">
    <h2 class="mb-0 fw-bold">Upload Payment Proof</h2>
    <a href="{{ route('cart.index') }}" class="btn btn-link">← Back to Cart</a>
  </div>

  {{-- Flash + Errors --}}
  @if (session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
  @endif
  @if (session('error'))
    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
  @endif
  @if ($errors->any())
    <div class="alert alert-danger mt-3">
      <ul class="mb-0">
        @foreach ($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="row g-4 mt-1">
    {{-- LEFT: Upload form --}}
    <div class="col-lg-7">
      <div class="card border-0 shadow">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h5 class="mb-0 fw-semibold">Submit Your Receipt / Screenshot</h5>
        </div>
        <div class="card-body">
          <div class="alert alert-info">
            Please pay <strong>{{ number_format($order->total,2) }} {{ $order->currency }}</strong>
            using <strong class="text-uppercase">{{ $transaction->gateway }}</strong>, then upload a clear image or PDF of your receipt.
          </div>

          {{-- If there is an existing proof, show a quick preview/link --}}
          @if(!empty($transaction->proof_path))
            <div class="mb-3">
              <label class="form-label">Current Proof</label>
              <div class="d-flex align-items-center gap-3">
                <a class="btn btn-outline-primary btn-sm"
                   target="_blank"
                   href="{{ asset('storage/'.$transaction->proof_path) }}">
                  View Uploaded Proof
                </a>
                <span class="badge text-bg-warning">Replacing this file will overwrite the current one</span>
              </div>
            </div>
          @endif

          <form method="POST"
                action="{{ route('checkout.proof.store', $transaction) }}"
                enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
              <label class="form-label">Payment Screenshot (JPG/PNG/WEBP/PDF, max 5MB)</label>
              <input type="file" name="proof" class="form-control" required>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Payer Name (optional)</label>
                <input type="text" name="payer_name" class="form-control" value="{{ old('payer_name') }}">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Payer Phone (optional)</label>
                <input type="text" name="payer_phone" class="form-control" value="{{ old('payer_phone') }}">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Payment Date &amp; Time (optional)</label>
                <input type="datetime-local" name="paid_at" class="form-control" value="{{ old('paid_at') }}">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Your Payment Reference # (optional)</label>
                <input type="text" name="reference" class="form-control" value="{{ old('reference') }}">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Note to Admin (optional)</label>
              <textarea name="note" rows="3" class="form-control">{{ old('note') }}</textarea>
            </div>

            <div class="d-flex gap-2">
              <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">Back</a>
              <button class="btn btn-primary">Submit for Review</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- RIGHT: Order & Transaction info --}}
    <div class="col-lg-5">
      <div class="card border-0 shadow mb-3">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h6 class="mb-0 fw-semibold">Order Summary</h6>
        </div>
        <div class="card-body">
          @if($order->items->count())
            <ul class="list-group list-group-flush mb-3">
              @foreach($order->items as $item)
                <li class="list-group-item d-flex justify-content-between">
                  <span class="text-truncate me-3">{{ $item->course->title ?? 'Course' }}</span>
                  <span>{{ number_format($item->price,2) }} {{ $order->currency }}</span>
                </li>
              @endforeach
            </ul>
          @endif

          <ul class="list-group list-group-flush mb-3">
            <li class="list-group-item d-flex justify-content-between">
              <span>Subtotal</span>
              <span>{{ number_format($order->subtotal,2) }} {{ $order->currency }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Discount</span>
              <span>-{{ number_format($order->discount,2) }} {{ $order->currency }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span class="fw-semibold">Total</span>
              <span class="fw-semibold">{{ number_format($order->total,2) }} {{ $order->currency }}</span>
            </li>
          </ul>

          <div class="alert alert-secondary mb-0">
            <div class="d-flex justify-content-between">
              <span class="text-muted">Gateway</span>
              <span class="text-uppercase">{{ $transaction->gateway }}</span>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-muted">Transaction Status</span>
              <span class="badge {{ $transaction->status==='submitted' ? 'text-bg-warning' : ($transaction->status==='captured' ? 'text-bg-success' : 'text-bg-secondary') }}">
                {{ strtoupper($transaction->status) }}
              </span>
            </div>
            @if(!empty($transaction->reference))
              <div class="d-flex justify-content-between">
                <span class="text-muted">Reference</span>
                <span>{{ $transaction->reference }}</span>
              </div>
            @endif
          </div>
        </div>
      </div>

      @if(!empty($transaction->proof_path))
        <div class="card border-0 shadow">
          <div class="card-header bg-white border-0 pt-3 pb-0">
            <h6 class="mb-0 fw-semibold">Last Uploaded Proof</h6>
          </div>
          <div class="card-body">
            <a class="btn btn-outline-primary w-100"
               target="_blank"
               href="{{ asset('storage/'.$transaction->proof_path) }}">
              Open Proof
            </a>
          </div>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
