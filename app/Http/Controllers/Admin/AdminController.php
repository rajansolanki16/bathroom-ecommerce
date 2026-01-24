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

        return view('admin.dashboard')
                ->with([
                    "user"=>$user,
                ]);
    }

    public function show_users(){
        $users = User::whereDoesntHave('roles', fn($q) => $q->where('name', 'admin'))->get();
        return view('admin.users.all')->with(["users"=>$users]);
    }
}
