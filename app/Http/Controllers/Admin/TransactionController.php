<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('order')->orderByDesc('created_at');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('reference', 'like', "%{$q}%")
                  ->orWhere('amount', 'like', "%{$q}%")
                  ->orWhere('currency', 'like', "%{$q}%");
            });
        }

        if ($request->filled('gateway') && $request->gateway !== '') {
            $query->where('gateway', $request->gateway);
        }

        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // ⬇️ No pagination — fetch ALL matching rows
        $tx = $query->get();

        // filter dropdown data
        $gateways = Transaction::whereNotNull('gateway')
            ->select('gateway')->distinct()->pluck('gateway')->sort()->values();

        $statuses = Transaction::whereNotNull('status')
            ->select('status')->distinct()->pluck('status')->sort()->values();

        return view('admin.pages.transactions.index', compact('tx','gateways','statuses'));
    }

    /** Approve a submitted proof: capture payment and grant access */
    public function approve(Request $request, Transaction $transaction)
    {
        $order = $transaction->order;

        // 1) finalize this proof transaction
        $transaction->update([
            'status'      => 'captured',
            'reference'   => $transaction->reference ?: 'PROOF-'.now()->timestamp,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_note' => $request->input('review_note'),
            'payload'     => array_merge($transaction->payload ?? [], ['approved' => true]),
        ]);

        // 2) mark order paid + ENROLL user (no duplicate tx, pass sourceTxId)
        if ($order) {
            $order->loadMissing('items','user');
            $order->markPaid(
                $transaction->reference,
                ['approved_via_proof' => true, 'source_tx' => $transaction->id],
                $transaction->gateway,
                $transaction->id // prevents creating another transaction row
            );
        }

        // 3) clear cart/coupon just in case
        session()->forget(['cart','coupon']);

        return back()->with('success','Payment approved and access granted.');
    }

    /** Reject a submitted proof */
    public function reject(Request $request, Transaction $transaction)
    {
        $transaction->update([
            'status'      => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_note' => $request->input('review_note'),
            'payload'     => array_merge($transaction->payload ?? [], ['approved' => false]),
        ]);

        return back()->with('success','Payment proof rejected.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('admin.transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}