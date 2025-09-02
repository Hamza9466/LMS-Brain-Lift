@extends('admin.layouts.main')

@section('content')
@php
    use Illuminate\Support\Str;
@endphp

<style>
  /* compact table spacing */
  .table > :not(caption) > * > * { padding-top: .6rem; padding-bottom: .6rem; }
  /* gradient header like your courses page */
  thead.tx-head { background: linear-gradient(90deg, #02409c, #12a0a0); color:#fff; }
  /* tiny icon actions (no big buttons) */
  .icon-actions a,
  .icon-actions button.btn-link {
    font-size: 1.05rem;
    line-height: 1;
    padding: .25rem .35rem;
    border-radius: .35rem;
  }
  .icon-actions a:hover { background: rgba(0,0,0,.05); }
  .icon-actions .text-danger:hover { background: rgba(220,53,69,.08); }
  .icon-actions .text-success:hover{ background: rgba(25,135,84,.08); }
  .icon-actions .text-warning:hover{ background: rgba(255,193,7,.12); }
  code.ref {
    background:#f8f9fa; border:1px solid #e9ecef; padding:.15rem .35rem; border-radius:.25rem;
    font-size:.85rem;
  }
</style>

<div class="container py-4">
  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0 text-primary">Transactions</h4>
   
  </div>

  {{-- Filters --}}
  <div class="card shadow-sm border-0 rounded-3 mb-3">
    <div class="card-body">
      <form method="GET" class="row g-3 align-items-end">
        <div class="col-lg-5">
          <label class="form-label mb-1">Search</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Reference, amount, currency">
          </div>
        </div>

        <div class="col-lg-2 col-md-6">
          <label class="form-label mb-1">Gateway</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-plug"></i></span>
            <select name="gateway" class="form-select">
              <option value="">All Gateways</option>
              @foreach($gateways as $g)
                <option value="{{ $g }}" @selected(request('gateway')===$g)>{{ ucfirst($g) }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <label class="form-label mb-1">Status</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-flag"></i></span>
            <select name="status" class="form-select">
              <option value="">All Statuses</option>
              @foreach($statuses as $s)
                <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="col-lg-1 col-12">
          <button class="btn btn-primary shadow-sm"><i class="fas fa-filter me-1"></i> Filter</button>
        </div>
      </form>
    </div>
  </div>

  {{-- Flash --}}
  @if (session('success')) <div class="alert alert-success"><i class="fas fa-check-circle me-1"></i>{{ session('success') }}</div> @endif
  @if (session('error'))   <div class="alert alert-danger"><i class="fas fa-triangle-exclamation me-1"></i>{{ session('error') }}</div> @endif
  @if ($errors->any())     <div class="alert alert-danger"><i class="fas fa-triangle-exclamation me-1"></i>{{ $errors->first() }}</div> @endif

  {{-- Table --}}
  <div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="tx-head">
            <tr>
              <th class="px-3 py-3">#</th>
              <th class="px-3 py-3">Order</th>
              <th class="py-3">Gateway</th>
              <th class="py-3">Status</th>
              <th class="py-3 text-end">Amount</th>
              <th class="py-3">Currency</th>
              <th class="py-3">Reference</th>
              <th class="py-3">Proof</th>
              <th class="py-3">Created</th>
              <th class="py-3 text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($tx as $t)
              @php
                $statusKey = strtolower($t->status ?? '');
                $statusMap = [
                  'pending'   => 'warning',
                  'submitted' => 'info',
                  'captured'  => 'success',
                  'rejected'  => 'danger',
                  'paid'      => 'success',
                  'failed'    => 'danger',
                  'created'   => 'secondary',
                  'authorized'=> 'info',
                ];
                $badge = $statusMap[$statusKey] ?? 'secondary';
              @endphp

              <tr class="border-bottom">
                <td class="px-3 fw-semibold">{{ $loop->iteration }}</td>

                <td class="px-3">
                  @if($t->order)
                    <div class="fw-semibold">#{{ $t->order->id }} <span class="text-muted">({{ ucfirst($t->order->status) }})</span></div>
                    <small class="text-muted">{{ number_format($t->order->total,2) }} {{ $t->order->currency }}</small>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>

                <td>
                  <span class="badge bg-secondary">{{ ucfirst($t->gateway) }}</span>
                </td>

                <td>
                  <span class="badge bg-{{ $badge }}">{{ ucfirst($t->status) }}</span>
                </td>

                <td class="text-end">{{ number_format((float)$t->amount,2) }}</td>
                <td class="text-uppercase">{{ $t->currency }}</td>

                <td>
                  @if($t->reference)
                    <code class="ref">{{ $t->reference }}</code>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>

                <td>
                  @if(!empty($t->proof_path))
                    <a class="btn btn-sm btn-outline-primary" target="_blank" href="{{ asset('storage/'.$t->proof_path) }}">
                      <i class="fas fa-eye me-1"></i> View
                    </a>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>

                <td title="{{ $t->created_at }}">{{ $t->created_at?->diffForHumans() ?? '—' }}</td>

                <td class="text-center">
                  <div class="icon-actions d-inline-flex align-items-center">
                    @if(in_array($statusKey, ['pending','submitted']))
                      <a href="#" class="text-success me-2" title="Approve"
                         data-bs-toggle="modal" data-bs-target="#approveModal-{{ $t->id }}">
                        <i class="fas fa-check-circle"></i>
                      </a>
                      <a href="#" class="text-danger me-2" title="Reject"
                         data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $t->id }}">
                        <i class="fas fa-times-circle"></i>
                      </a>
                    @endif

                    <form action="{{ route('admin.transactions.destroy', $t->id) }}" method="POST" class="d-inline">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn btn-link text-danger p-0 m-0" title="Delete"
                              onclick="return confirm('Delete this transaction?')">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="10" class="text-center text-muted py-4">No transactions found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- ⛔ No pagination links here --}}
</div>

{{-- ========================= MODALS ========================= --}}
@foreach ($tx as $t)
  {{-- Approve Modal --}}
  <div class="modal fade" id="approveModal-{{ $t->id }}" tabindex="-1" aria-hidden="true"
       data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <form method="POST" action="{{ route('admin.transactions.approve', $t) }}" class="modal-content">
        @csrf
        <div class="modal-header bg-success-subtle">
          <h5 class="modal-title mb-0"><i class="fas fa-check-circle text-success me-2"></i>Approve Transaction #{{ $t->id }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="row g-4">
            <div class="col-lg-7">
              @if(!empty($t->proof_path))
                <img src="{{ asset('storage/'.$t->proof_path) }}" class="img-fluid rounded w-100" alt="Payment proof"
                     onerror="this.style.display='none'">
                <a href="{{ asset('storage/'.$t->proof_path) }}" class="btn btn-outline-primary btn-sm mt-2" target="_blank">
                  <i class="fas fa-up-right-from-square me-1"></i> Open proof in new tab
                </a>
              @else
                <div class="alert alert-warning mb-0"><i class="fas fa-triangle-exclamation me-1"></i>No proof uploaded.</div>
              @endif
            </div>

            <div class="col-lg-5">
              <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between">
                  <span>Amount</span>
                  <span class="fw-semibold">{{ number_format((float)$t->amount,2) }} {{ $t->currency }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <span>Gateway</span>
                  <span class="text-uppercase">{{ $t->gateway }}</span>
                </li>
                @if($t->order)
                  <li class="list-group-item d-flex justify-content-between">
                    <span>Order</span>
                    <span>#{{ $t->order->id }} ({{ ucfirst($t->order->status) }})</span>
                  </li>
                @endif
              </ul>

              <label class="form-label">Review Note (optional)</label>
              <textarea name="review_note" class="form-control" rows="5" placeholder="Any note for audit..."></textarea>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fas fa-xmark me-1"></i> Cancel
          </button>
          <button class="btn btn-success">
            <i class="fas fa-check-double me-1"></i> Approve &amp; Grant Access
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- Reject Modal --}}
  <div class="modal fade" id="rejectModal-{{ $t->id }}" tabindex="-1" aria-hidden="true"
       data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <form method="POST" action="{{ route('admin.transactions.reject', $t) }}" class="modal-content">
        @csrf
        <div class="modal-header bg-warning-subtle">
          <h5 class="modal-title mb-0"><i class="fas fa-ban text-danger me-2"></i>Reject Transaction #{{ $t->id }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Reason / Note (optional)</label>
            <textarea name="review_note" class="form-control" rows="5" placeholder="Why is this rejected?"></textarea>
          </div>
          <div class="alert alert-warning mb-0">
            This will set the transaction status to <strong>rejected</strong>.
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fas fa-xmark me-1"></i> Cancel
          </button>
          <button class="btn btn-danger">
            <i class="fas fa-xmark me-1"></i> Reject
          </button>
        </div>
      </form>
    </div>
  </div>
@endforeach
{{-- ========================================================= --}}
@endsection
