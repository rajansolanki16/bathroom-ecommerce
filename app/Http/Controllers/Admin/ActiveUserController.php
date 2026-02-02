<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActiveUser;

class ActiveUserController extends Controller
{
    public function index()
    {
        $activeUsers = ActiveUser::getActive(120); // 2 hours
        return view('admin.active_users.index', compact('activeUsers'));
    }
}
