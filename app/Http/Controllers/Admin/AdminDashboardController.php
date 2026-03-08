<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalTenants = Tenant::count();
        $totalUsers = User::count();
        $totalAppointments = Appointment::count();
        $recentTenants = Tenant::latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalTenants', 'totalUsers', 'totalAppointments', 'recentTenants', 'recentUsers'
        ));
    }
}
