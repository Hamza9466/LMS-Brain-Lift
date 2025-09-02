<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PurchaseHistoryController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user();

    $base = Order::query()->latest('created_at');

    if ($user->role === 'admin') {
        // Admin sees everything
        $orders = $base->with(['items.course'])->paginate(10);

    } elseif ($user->role === 'teacher') {
        // Teacher: only orders that include THIS teacher's courses
        $orders = $base
            ->whereHas('items.course', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })
            // Load ONLY this teacher's items inside each order
            ->with(['items' => function ($q) use ($user) {
                $q->whereHas('course', function ($cq) use ($user) {
                    $cq->where('teacher_id', $user->id);
                })->with('course');
            }])
            ->paginate(10);

    } else {
        // Student: only their own orders
        $orders = $base
            ->where('user_id', $user->id)
            ->with(['items.course'])
            ->paginate(10);
    }

    return view('admin.pages.purchase-history', compact('orders'));
}
}