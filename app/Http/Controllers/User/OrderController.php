<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderAudit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use App\Enums\OrderStatus;
use App\Exports\OrdersExport;
use Illuminate\Support\Facades\Log;

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
    public function indexshow(Request $request)
    {
        $statuses = OrderStatus::cases();

        // Base query
        $query = Order::with('user', 'items.product');

        // Filter by status (status stored as raw value in DB)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search filtering (user name or product title)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->orWhereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })->orWhereHas('items.product', function ($q3) use ($search) {
                    $q3->where('product_title', 'like', "%{$search}%");
                });
            });
        }

        // Filter by date range
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
        } elseif ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        } elseif ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Paginate the results and keep query string for pagination links
        $perPage = (int) $request->input('per_page', 15);
        $orders = $query->latest()->paginate($perPage)->withQueryString();

        return view('admin.orders.show', compact('orders', 'statuses'));
    }
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', new Enum(OrderStatus::class)],
        ]);

        $oldLabel = optional($order->status)->label();

        $order->status = OrderStatus::from($request->status);
        $order->save();

        // Audit log
        try {
            OrderAudit::create([
                'order_id' => $order->id,
                'admin_id' => auth()->id(),
                'field' => 'status',
                'old_value' => $oldLabel,
                'new_value' => $order->status->label(),
            ]);
        } catch (\Throwable $e) {
            Log::error('OrderAudit::create failed for status update', ['order' => $order->id, 'error' => $e->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'status'  => $order->status->label(),
        ]);
    }

    public function updateNotes(Request $request, Order $order)
    {
        Log::info('updateNotes called', ['order' => $order->id, 'payload' => $request->all(), 'admin' => auth()->id()]);

        $request->validate([
            'internal_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $old = $order->internal_notes;
        $order->internal_notes = $request->internal_notes;
        $order->save();

        try {
            OrderAudit::create([
                'order_id' => $order->id,
                'admin_id' => auth()->id(),
                'field' => 'internal_notes',
                'old_value' => $old,
                'new_value' => $order->internal_notes,
            ]);
        } catch (\Throwable $e) {
            Log::error('OrderAudit::create failed for notes update', ['order' => $order->id, 'error' => $e->getMessage()]);
        }

        Log::info('updateNotes saved', ['order' => $order->id, 'admin' => auth()->id()]);

        return response()->json([
            'success' => true,
            'internal_notes' => $order->internal_notes,
        ]);
    }

    // Export orders as CSV or Excel
    public function export(string $type = 'csv')
    {
        $type = strtolower($type);

        // Build the same filtered query as the listing so exports match filters
        $query = Order::with(['user', 'items.product']);

        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('items.product', fn($q3) => $q3->where('product_title', 'like', "%{$search}%"));
            });
        }

        if (request()->filled('from_date') && request()->filled('to_date')) {
            $query->whereBetween('created_at', [request('from_date') . ' 00:00:00', request('to_date') . ' 23:59:59']);
        } elseif (request()->filled('from_date')) {
            $query->whereDate('created_at', '>=', request('from_date'));
        } elseif (request()->filled('to_date')) {
            $query->whereDate('created_at', '<=', request('to_date'));
        }

        $orders = $query->get();

        // EXCEL EXPORT
        if ($type === 'excel') {
            $exporter = new OrdersExport();
            $xlsxPath = $exporter->generateExcelXlsx($orders);

            if ($xlsxPath && file_exists($xlsxPath)) {
                return response()->download($xlsxPath, 'orders.xlsx', [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ])->deleteFileAfterSend(true);
            }

            return response()->json(['error' => 'Failed to generate Excel file'], 500);
        }

        // CSV EXPORT (DEFAULT)
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
