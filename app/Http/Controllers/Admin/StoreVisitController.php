<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreVisit;
use App\Models\User;
use App\Exports\StoreVisitsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class StoreVisitController extends Controller
{
    public function index(Request $request)
    {
        $query = StoreVisit::with(['salesman', 'vendor'])->orderByDesc('id');
        $vendors = User::whereHas('roles', fn($q) => $q->where('name', 'vendor'))->get();
        $salesmen = User::whereHas('roles', fn($q) => $q->where('name', 'salesman'))->get();
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }
        if ($request->filled('salesman_id')) {
            $query->where('salesman_id', $request->salesman_id);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        $visits = $query->paginate(20);
        return view('admin.salesman.visit_report', compact('visits', 'vendors', 'salesmen'));
    }

    public function show(StoreVisit $storeVisit)
    {
        return view('admin.salesman.view_visit_report', compact('storeVisit'));
    }

    public function approve($id)
    {
        $visit = StoreVisit::findOrFail($id);
        $visit->update(['is_approve' => 1]);

        return redirect()->back()->with('success', 'Visit approved successfully!');
    }

    public function reject($id)
    {
        $visit = StoreVisit::findOrFail($id);
        $visit->update(['is_approve' => 0]);

        return redirect()->back()->with('success', 'Visit rejected successfully!');
    }

    public function exportExcel(Request $request)
    {
        $filters = [
            'salesman_id' => $request->salesman_id,
            'vendor_id' => $request->vendor_id,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'is_approve' => $request->is_approve,
            'user' => Auth::user(),
        ];

        $filename = 'store_visits_' . date('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new StoreVisitsExport($filters), $filename);
    }

    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $query = StoreVisit::with(['vendor', 'salesman']);

        // Apply user role filters
        if ($user->hasRole('salesman')) {
            $query->where('salesman_id', $user->id);
        } elseif ($user->hasRole('vendor')) {
            $query->where('vendor_id', $user->id);
        }

        // Apply filters from request
        if ($request->filled('salesman_id')) {
            $query->where('salesman_id', $request->salesman_id);
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $visits = $query->latest()->get();

        $pdf = Pdf::loadView('admin.salesman.pdf', compact('visits'))
                  ->setPaper('a4', 'landscape');
        
        return $pdf->download('store_visits_' . date('Y-m-d_His') . '.pdf');
    }
}
