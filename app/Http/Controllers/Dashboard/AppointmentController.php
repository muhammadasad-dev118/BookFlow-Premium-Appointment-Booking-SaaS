<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = session('tenant_id');
        $query = Appointment::where('tenant_id', $tenantId)
            ->with(['service', 'staff', 'customer'])
            ->orderByDesc('start_time');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('service', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->paginate(15);
        return view('dashboard.appointments.index', compact('appointments'));
    }

    public function create()
    {
        return view('dashboard.appointments.form', ['appointment' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'staff_id' => 'required|exists:staff,id',
            'start_time' => 'required|date',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string',
        ]);

        $validated['tenant_id'] = session('tenant_id');

        // Calculate end_time from service duration
        $service = \App\Models\Service::find($validated['service_id']);
        $validated['end_time'] = \Carbon\Carbon::parse($validated['start_time'])->addMinutes($service->duration_minutes);

        Appointment::create($validated);
        return redirect()->route('appointments.index')->with('success', 'Appointment created successfully.');
    }

    public function edit(Appointment $appointment)
    {
        return view('dashboard.appointments.form', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'staff_id' => 'required|exists:staff,id',
            'start_time' => 'required|date',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string',
        ]);

        $service = \App\Models\Service::find($validated['service_id']);
        $validated['end_time'] = \Carbon\Carbon::parse($validated['start_time'])->addMinutes($service->duration_minutes);

        $appointment->update($validated);
        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment deleted.');
    }
}
