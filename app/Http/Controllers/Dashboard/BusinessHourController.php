<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BusinessHour;
use App\Models\Staff;
use Illuminate\Http\Request;

class BusinessHourController extends Controller
{
    public function index()
    {
        $tenantId = session('tenant_id');
        $businessHours = BusinessHour::where('tenant_id', $tenantId)
            ->with('staff')
            ->orderBy('staff_id')
            ->orderBy('day_of_week')
            ->get()
            ->groupBy('staff_id');

        $staff = Staff::where('tenant_id', $tenantId)->where('is_active', true)->get();
        return view('dashboard.business-hours.index', compact('businessHours', 'staff'));
    }

    public function create()
    {
        $tenantId = session('tenant_id');
        $staff = Staff::where('tenant_id', $tenantId)->where('is_active', true)->get();
        return view('dashboard.business-hours.form', ['businessHour' => null, 'staff' => $staff]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
        $validated['tenant_id'] = session('tenant_id');

        BusinessHour::create($validated);
        return redirect()->route('business-hours.index')->with('success', 'Business hours added.');
    }

    public function edit(BusinessHour $businessHour)
    {
        $tenantId = session('tenant_id');
        $staff = Staff::where('tenant_id', $tenantId)->where('is_active', true)->get();
        return view('dashboard.business-hours.form', compact('businessHour', 'staff'));
    }

    public function update(Request $request, BusinessHour $businessHour)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $businessHour->update($validated);
        return redirect()->route('business-hours.index')->with('success', 'Business hours updated.');
    }

    public function destroy(BusinessHour $businessHour)
    {
        $businessHour->delete();
        return redirect()->route('business-hours.index')->with('success', 'Business hours removed.');
    }
}
