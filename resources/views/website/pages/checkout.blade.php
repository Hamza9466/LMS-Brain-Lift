@extends('website.layouts.main')

@section('content')
@php
$currency = env('APP_CURRENCY','USD');
$cart     = $cart ?? session('cart', []);
@endphp

<div class="container py-4">
  <h2 class="mb-3 fw-bold">Checkout</h2>

  {{-- Messages --}}
  @if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif
  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card border-0 shadow">
        <div class="card-header bg-white border-0 pt-3">
          <h5 class="mb-0">Order Summary</h5>
        </div>
        <div class="card-body">
          <table class="table align-middle">
            <thead class="table-light">
              <tr>
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
                @endphp
                <tr>
                  <td>
                    <div class="fw-semibold">{{ $row['title'] ?? 'Course' }}</div>
                    @if(!empty($row['slug'])) <div class="small text-muted">/{{ $row['slug'] }}</div> @endif
                  </td>
                  <td class="text-center">{{ $qty }}</td>
                  <td class="text-end">{{ number_format($unitPrice * $qty, 2) }} {{ $currency }}</td>
                </tr>
              @empty
                <tr><td colspan="3" class="text-center text-muted py-4">Your cart is empty.</td></tr>
              @endforelse
            </tbody>
            <tfoot class="table-group-divider">
              <tr>
                <th class="text-muted fw-normal">Subtotal</th><th></th>
                <th class="text-end fw-semibold">{{ number_format($subtotal,2) }} {{ $currency }}</th>
              </tr>
              <tr>
               
                
              </tr>
              @if($couponDiscount > 0)
              <tr>
                <th class="text-muted fw-normal">
                  Discount <span class="small text-muted">(Coupon{{ !empty($coupon['code']) ? ': '.$coupon['code'] : '' }})</span>
                </th><th></th>
                <th class="text-end text-success fw-semibold">-{{ number_format($couponDiscount,2) }} {{ $currency }}</th>
              </tr>
              @endif
              <tr class="table-light">
                <th class="fs-5">Total</th><th></th>
                <th class="text-end fs-4 fw-bold">{{ number_format($total,2) }} {{ $currency }}</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      {{-- Coupon --}}
      <div class="card border-0 shadow mb-3">
        <div class="card-header bg-white border-0 pt-3">
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
                Applied coupon: <span class="badge text-bg-primary">{{ $coupon['code'] }}</span>
              </div>
            @endif
          </form>
        </div>
      </div>

      {{-- Payment --}}
      <div class="card border-0 shadow">
        <div class="card-header bg-white border-0 pt-3">
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
                <option value="FaysalBank"
                        data-type="bank"
                        data-account="3165301000004290"
                        data-name="Muhammad Ushaq Ilyas"
                        data-iban="PK25FAYS3165301000004290"
                        {{ old('gateway')==='FaysalBank' ? 'selected' : '' }}>
                  Faysal Bank (Manual Transfer)
                </option>
              </select>
            </div>

            <div class="alert alert-secondary d-flex justify-content-between align-items-center mb-3">
              <span class="text-muted">Amount to pay</span>
              <span class="fw-semibold">{{ number_format($total,2) }} {{ $currency }}</span>
            </div>

            <div id="bankBox" class="card border-0 shadow-sm mb-3 d-none">
              <div class="card-body">
                <h6 class="mb-3">Bank Transfer Details</h6>
                <ul class="list-unstyled mb-0 small">
                  <li class="mb-1"><strong>Account No:</strong> <span id="accNo">—</span>
                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2" data-copy="#accNo">Copy</button>
                  </li>
                  <li class="mb-1"><strong>Name:</strong> <span id="accName">—</span>
                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2" data-copy="#accName">Copy</button>
                  </li>
                  <li class="mb-1"><strong>IBAN:</strong> <span id="iban">—</span>
                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2" data-copy="#iban">Copy</button>
                  </li>
                </ul>
                <div class="alert alert-info mt-3 py-2">
                  After sending, please upload the receipt on the next page.
                </div>
              </div>
            </div>

            <button class="btn btn-success btn-lg w-100" {{ count($cart)?'':'disabled' }}>Buy Now</button>
          </form>

          <script>
          document.addEventListener('DOMContentLoaded', function () {
            const select  = document.getElementById('gateway');
            const box     = document.getElementById('bankBox');
            const accNo   = document.getElementById('accNo');
            const accName = document.getElementById('accName');
            const iban    = document.getElementById('iban');

            function toggleBankBox() {
              const opt = select.options[select.selectedIndex];
              const isBank = opt && opt.dataset.type === 'bank';
              if (isBank) {
                accNo.textContent   = opt.dataset.account || '';
                accName.textContent = opt.dataset.name || '';
                iban.textContent    = opt.dataset.iban || '';
                box.classList.remove('d-none');
              } else {
                box.classList.add('d-none');
              }
            }

            document.addEventListener('click', function (e) {
              const btn = e.target.closest('[data-copy]');
              if (!btn) return;
              const target = document.querySelector(btn.getAttribute('data-copy'));
              if (!target) return;
              navigator.clipboard.writeText(target.textContent.trim()).then(() => {
                const original = btn.textContent;
                btn.textContent = 'Copied';
                setTimeout(() => btn.textContent = original, 1200);
              });
            });

            select.addEventListener('change', toggleBankBox);
            toggleBankBox(); // on load
          });
          </script>
          @endauth

          @guest
            <div class="alert alert-info mb-0">
              Please <a href="{{ route('login', ['intended' => route('cart.checkout')]) }}">log in</a> to complete your purchase.
            </div>
          @endguest
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
