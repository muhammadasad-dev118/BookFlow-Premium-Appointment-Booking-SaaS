<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tenantId = session('tenant_id');

        // Stat cards
        $totalBookings = Appointment::where('appointments.tenant_id', $tenantId)->count();

        // Revenue - use a safe approach that doesn't require payments table
        $revenue = 0;
        try {
            $revenue = DB::table('payments')
                ->join('appointments', 'payments.appointment_id', '=', 'appointments.id')
                ->where('appointments.tenant_id', $tenantId)
                ->where('payments.status', 'completed')
                ->sum('payments.amount');
        } catch (\Exception $e) {
            // payments table may not exist yet
        }

        $activeClients = Customer::where('tenant_id', $tenantId)->count();
        $staffCount = Staff::where('tenant_id', $tenantId)->where('is_active', true)->count();

        // Upcoming appointments
        $upcomingAppointments = Appointment::where('appointments.tenant_id', $tenantId)
            ->where('start_time', '>=', now())
            ->with(['service', 'staff', 'customer'])
            ->orderBy('start_time')
            ->take(8)
            ->get();

        // Revenue chart data (last 6 months) - safe approach
        $revenueData = [0, 0, 0, 0, 0, 0];
        $revenueLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenueLabels[] = $month->format('M');
            try {
                $revenueData[5 - $i] = (float) DB::table('payments')
                    ->join('appointments', 'payments.appointment_id', '=', 'appointments.id')
                    ->where('appointments.tenant_id', $tenantId)
                    ->whereYear('appointments.start_time', $month->year)
                    ->whereMonth('appointments.start_time', $month->month)
                    ->where('payments.status', 'completed')
                    ->sum('payments.amount');
            } catch (\Exception $e) {
                // ignore
            }
        }

        // Bookings by service (donut chart)
        $serviceStats = Service::where('tenant_id', $tenantId)
            ->withCount('appointments')
            ->orderByDesc('appointments_count')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalBookings', 'revenue', 'activeClients', 'staffCount',
            'upcomingAppointments', 'revenueData', 'revenueLabels', 'serviceStats'
        ));
    }
}
