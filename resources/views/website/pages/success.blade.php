@extends('website.layouts.main')

@section('content')
<div class="container py-4">
  <h2 class="mb-3">Thank you! Enrollment confirmed.</h2>

  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-header fw-semibold">Order #{{ $order->id }}</div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table mb-0">
          <thead>
            <tr>
              <th>Course</th>
              <th class="text-end">Price</th>
            </tr>
          </thead>
          <tbody>
            @foreach($order->items as $it)
              <tr>
                <td>Course #{{ $it->course_id }}</td>
                <td class="text-end">{{ number_format((float)$it->price, 2) }} {{ $order->currency }}</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>Subtotal</th>
              <th class="text-end">{{ number_format((float)$order->subtotal, 2) }} {{ $order->currency }}</th>
            </tr>
            <tr>
              <th>Discount</th>
              <th class="text-end">-{{ number_format((float)$order->discount, 2) }} {{ $order->currency }}</th>
            </tr>
            <tr>
              <th class="fs-5">Total</th>
              <th class="text-end fs-5">{{ number_format((float)$order->total, 2) }} {{ $order->currency }}</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>

  <div class="mt-3 d-flex gap-2">
    <a class="btn btn-success" href="{{ route('cart.index') }}">Back to Cart</a>
    {{-- change to your "My Courses" route if you have one --}}
  </div>
</div>
@endsection
