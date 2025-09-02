@extends('website.layouts.main')

@section('content')
@php
  $currency = $order->currency ?? env('APP_CURRENCY','USD');
  $isSubmitted = strtolower($transaction->status ?? '') === 'submitted';
  $proofUrl = !empty($transaction->proof_path) ? asset('storage/'.$transaction->proof_path) : null;

  // simple helper to guess if proof is image
  $ext = $transaction->proof_path ? strtolower(pathinfo($transaction->proof_path, PATHINFO_EXTENSION)) : '';
  $isImage = in_array($ext, ['jpg','jpeg','png','webp']);
@endphp

<section class="py-3 bg-light border-bottom">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cart.checkout') }}">Checkout</a></li>
        <li class="breadcrumb-item active" aria-current="page">Upload Proof</li>
      </ol>
    </nav>
  </div>
</section>

<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">Upload Payment Proof</h3>
    <a href="{{ route('cart.checkout') }}" class="btn btn-link">← Back to Checkout</a>
  </div>

  {{-- Flash + errors --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  @if($isSubmitted)
    <div class="alert alert-info">
      Thanks! Your proof was submitted and is pending review. You can re-submit to replace it.
    </div>
  @endif

  <div class="row g-4">
    {{-- LEFT: Order summary --}}
    <div class="col-lg-7">
      <div class="card border-0 shadow">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h5 class="mb-0">Order #{{ $order->id ?? '—' }}</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead class="table-light">
                <tr class="text-muted">
                  <th>Course</th>
                  <th class="text-end" style="width:180px;">Price</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                @forelse($order->items ?? [] as $it)
                  <tr>
                    <td class="text-truncate">{{ optional($it->course)->title ?? 'Course' }}</td>
                    <td class="text-end">{{ number_format((float)$it->price,2) }} {{ $currency }}</td>
                  </tr>
                @empty
                  <tr><td colspan="2" class="text-center text-muted py-4">No items.</td></tr>
                @endforelse
              </tbody>
              <tfoot class="table-group-divider">
                <tr>
                  <th class="text-muted fw-normal">Subtotal</th>
                  <th class="text-end fw-semibold">{{ number_format((float)$order->subtotal,2) }} {{ $currency }}</th>
                </tr>
                <tr>
                  <th class="text-muted fw-normal">Discount</th>
                  <th class="text-end text-success fw-semibold">-{{ number_format((float)$order->discount,2) }} {{ $currency }}</th>
                </tr>
                <tr class="table-light">
                  <th class="fs-5">Total</th>
                  <th class="text-end fs-5 fw-bold">{{ number_format((float)$order->total,2) }} {{ $currency }}</th>
                </tr>
              </tfoot>
            </table>
          </div>

          <div class="row g-3 mt-3">
            <div class="col-sm-6">
              <div class="border rounded p-3">
                <div class="text-muted">Gateway</div>
                <div class="fw-semibold text-uppercase">{{ $transaction->gateway }}</div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="border rounded p-3">
                <div class="text-muted">Amount to pay</div>
                <div class="fw-semibold">{{ number_format((float)$transaction->amount,2) }} {{ $currency }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- RIGHT: Upload form / preview --}}
    <div class="col-lg-5">
      <div class="card border-0 shadow">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h5 class="mb-0">Payment Screenshot</h5>
        </div>
        <div class="card-body">
          @if($proofUrl)
            <div class="mb-3">
              <div class="text-muted mb-1">Current proof:</div>
              @if($isImage)
                <a href="{{ $proofUrl }}" target="_blank" class="d-inline-block">
                  <img src="{{ $proofUrl }}" class="img-fluid rounded border" alt="Proof">
                </a>
              @else
                <a href="{{ $proofUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">View uploaded file</a>
              @endif
            </div>
            <hr>
          @endif

          <form method="POST" action="{{ route('checkout.proof.store', $transaction) }}" enctype="multipart/form-data" class="row g-3">
            @csrf

            <div class="col-12">
              <label class="form-label">Upload file (JPG, PNG, WEBP or PDF, max 5MB)</label>
              <input type="file" name="proof" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf" required>
            </div>

            <div class="col-12">
              <label class="form-label">Payer name (optional)</label>
              <input type="text" name="payer_name" class="form-control" value="{{ old('payer_name') }}">
            </div>

            <div class="col-md-6">
              <label class="form-label">Phone (optional)</label>
              <input type="text" name="payer_phone" class="form-control" value="{{ old('payer_phone') }}">
            </div>

            <div class="col-md-6">
              <label class="form-label">Paid at (optional)</label>
              <input type="datetime-local" name="paid_at" class="form-control" value="{{ old('paid_at') }}">
            </div>

            <div class="col-12">
              <label class="form-label">Payment reference (optional)</label>
              <input type="text" name="reference" class="form-control" value="{{ old('reference') }}">
            </div>

            <div class="col-12">
              <label class="form-label">Note (optional)</label>
              <textarea name="note" rows="3" class="form-control" placeholder="Any helpful info…">{{ old('note') }}</textarea>
            </div>

            <div class="col-12 d-grid">
              <button class="btn btn-primary btn-lg">Submit Proof</button>
            </div>
          </form>

          <div class="alert alert-secondary mt-3 mb-0 small">
            After you submit, our team will review and approve. You’ll get access to your course once approved.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
