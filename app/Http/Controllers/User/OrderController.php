<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Orders list page
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }
    public function indexshow()
    {
        $orders = Order::with('user', 'product')->get();
        $statuses = OrderStatus::cases();

        //filtering status
        $query = Order::with('user', 'product', 'items.product');
        $query->when(request()->filled('status'), function ($q) {
            $q->where('status', OrderStatus::from(request('status')));
        });

        // Search filtering
        $query->when(request()->filled('search'), function ($q) {
            $search = request('search');

            $q->where(function ($query) use ($search) {
                // Search in user name 
                $query->orWhereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', '%' . $search . '%');
                });

                // Search in product name through order items
                $query->orWhereHas('items.product', function ($q3) use ($search) {
                    $q3->where('product_title', 'like', '%' . $search . '%');
                });
            });
        });

        // Filter by date range
        $query->when(request()->filled('from_date') && request()->filled('to_date'), function ($q) {
            $q->whereBetween('created_at', [request('from_date') . ' 00:00:00', request('to_date') . ' 23:59:59']);
        })->when(request()->filled('from_date') && !request()->filled('to_date'), function ($q) {
            $q->whereDate('created_at', '>=', request('from_date'));
        })->when(!request()->filled('from_date') && request()->filled('to_date'), function ($q) {
            $q->whereDate('created_at', '<=', request('to_date'));
        });

        $orders = $query->get();

        return view('admin.orders.show', compact('orders', 'statuses'));
    }
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', new Enum(OrderStatus::class)],
        ]);

        $order->status = OrderStatus::from($request->status);
        $order->save();

        return response()->json([
            'success' => true,
            'status'  => $order->status->label(),
        ]);
    }
}
