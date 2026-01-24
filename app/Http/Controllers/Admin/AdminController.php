<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\User;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function show_admin(){
        $user = Auth::user();

        //total booking count
        $bookingCount = Booking::whereHas('transaction', function ($query) {
            $query->where('status', 1);
        })->count();

        //last month booking count
        $month_bookingCount = Booking::whereHas('transaction', function ($query) {
            $query->where('status', 1);
        })->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
        ->count();

        //total revenue
        $totalAmount = Transaction::where('status', 1)->sum('amount');
        
        //last month revenue
        $month_totalAmount = Transaction::where('status', 1)->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->sum('amount');

        //today's checkin
        $today_checkins = Booking::whereHas('transaction', function ($query) {
            $query->where('status', 1);
        })->whereDate('check_in', now()->toDateString())->get();
        

        //current checkin
        $current_checkins = Booking::whereHas('transaction', function ($query) {
            $query->where('status', 1);
        })
        ->whereDate('check_in', '<=', now()->toDateString()) // Check-in is today or before today
        ->whereDate('check_out', '>=', now()->toDateString()) // Check-out is today or after today
        ->get();

        return view('admin.dashboard')
                ->with([
                    "user"=>$user,
                    "bookingCount"=>$bookingCount,
                    "month_bookingCount"=>$month_bookingCount,
                    "totalAmount"=>$totalAmount,
                    "month_totalAmount"=>$month_totalAmount,
                    "today_checkins"=>$today_checkins,
                    "current_checkins"=>$current_checkins,
                ]);
    }

    public function show_users(){
        $users = User::whereDoesntHave('roles', fn($q) => $q->where('name', 'admin'))->get();
        return view('admin.users.all')->with(["users"=>$users]);
    }
}
