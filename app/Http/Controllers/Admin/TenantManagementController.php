<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::withCount(['users', 'appointments', 'staff']);

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $tenants = $query->latest()->paginate(15);
        return view('admin.tenants.index', compact('tenants'));
    }
}
