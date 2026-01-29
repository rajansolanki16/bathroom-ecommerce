<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use App\Enums\OrderStatus;
use App\Exports\OrdersExport;

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

    // Export orders as CSV or Excel
    public function export(string $type = 'csv')
    {
        // Normalize type
        $type = strtolower($type);

        // =====================
        // EXCEL EXPORT (XLSX)
        // =====================
        if ($type === 'excel') {
            $exporter = new OrdersExport();
            $xlsxPath = $exporter->generateExcelXlsx();

            if ($xlsxPath && file_exists($xlsxPath)) {
                return response()->download($xlsxPath, 'orders.xlsx', [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ])->deleteFileAfterSend(true);
            }

            return response()->json(['error' => 'Failed to generate Excel file'], 500);
        }

        // =====================
        // CSV EXPORT (DEFAULT)
        // =====================
        $orders = Order::with(['user', 'items.product'])->get();

        return response()->stream(function () use ($orders) {
            $handle = fopen('php://output', 'w');

            // Write BOM for proper UTF-8 encoding in Excel
            fwrite($handle, "\xEF\xBB\xBF");

            // Write headers
            $headers = [
                'Order ID',
                'Customer Name',
                'Full Name',
                'Email',
                'Phone',
                'Products',
                'Total',
                'Status',
                'Order Date',
            ];
            fputcsv($handle, $headers);

            // Write data rows
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->id,
                    $order->user->name ?? $order->name,
                    $order->name,
                    $order->email,
                    $order->phone,
                    $order->items->pluck('product.product_title')->implode(', '),
                    $order->total,
                    optional($order->status)->label(),
                    $order->created_at->format('Y-m-d'),
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="orders.csv"',
        ]);
    }

    // Order details page
    public function show($id) {
        $order = Order::with('items.product')->findOrFail($id);
            $statuses = OrderStatus::cases();

    return view('user.orders.details', compact('order','statuses'));
    }
}
