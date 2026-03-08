<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = session('tenant_id');
        $query = Staff::where('tenant_id', $tenantId)->withCount('appointments');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $staff = $query->paginate(15);
        return view('dashboard.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('dashboard.staff.form', ['staffMember' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);
        $validated['tenant_id'] = session('tenant_id');
        $validated['is_active'] = $request->boolean('is_active');

        Staff::create($validated);
        return redirect()->route('staff.index')->with('success', 'Staff member added.');
    }

    public function edit(Staff $staff)
    {
        return view('dashboard.staff.form', ['staffMember' => $staff]);
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        $staff->update($validated);
        return redirect()->route('staff.index')->with('success', 'Staff member updated.');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Staff member removed.');
    }
}
