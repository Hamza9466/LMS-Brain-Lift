@extends('admin.layouts.main')

@section('content')

@php
// Helpers to format money and status classes
if (!function_exists('currency_symbol')) {
    function currency_symbol($code) {
        return [
            'USD' => '$', 'EUR' => '€', 'GBP' => '£',
            'PKR' => '₨', 'INR' => '₹', 'AED' => 'د.إ',
        ][$code] ?? '$';
    }
}
if (!function_exists('price_parts')) {
    function price_parts($amount) {
        $amount = number_format((float)$amount, 2, '.', '');
        [$major, $minor] = explode('.', $amount);
        return [$major, $minor];
    }
}
if (!function_exists('status_label_class')) {
    function status_label_class($status) {
        $map = [
            'paid'      => 'success',
            'completed' => 'success',
            'cancelled' => 'danger',
            'on_hold'   => 'warning',
            'hold'      => 'warning',
            'pending'   => 'secondary',
            'processing'=> 'secondary',
        ];
        return $map[strtolower($status ?? '')] ?? 'secondary';
    }
}
if (!function_exists('status_display_text')) {
    function status_display_text($status) {
        $s = strtolower($status ?? '');
        return match ($s) {
            'paid', 'completed' => 'Completed',
            'cancelled'         => 'Cancelled',
            'on_hold', 'hold'   => 'On Hold',
            'pending', 'processing', '' => 'Processing',
            default             => ucfirst($s),
        };
    }
}
@endphp

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-primary">Purchase History</h4>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead style="background: linear-gradient(90deg, #02409c, #12a0a0); color: #fff;">
                        <tr>
                            <th class="px-3 py-3">#</th>
                            <th class="py-3">Courses</th>
                            <th class="py-3">Amount</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            @php
                                $symbol     = currency_symbol($order->currency ?? 'USD');
                                [$major,$minor] = price_parts($order->total ?? 0);
                                $statusClass = status_label_class($order->status);
                                $statusText  = status_display_text($order->status);
                                $dateStr     = optional($order->created_at)->format('F j, Y');
                            @endphp
                            <tr class="border-bottom">
                                <td class="px-3">#{{ $loop->iteration }}</td>
                                <td>
                                    @forelse(($order->items ?? collect()) as $it)
                                        <p class="mb-0">{{ $it->course->title ?? ('Course #'.$it->course_id) }}</p>
                                    @empty
                                        <span class="text-muted">—</span>
                                    @endforelse
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $symbol }}{{ $major }}</span>
                                    <small class="text-muted">.{{ $minor }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td>{{ $dateStr }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No purchases yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if ($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="d-flex justify-content-center mt-3">
            {{ $orders->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
