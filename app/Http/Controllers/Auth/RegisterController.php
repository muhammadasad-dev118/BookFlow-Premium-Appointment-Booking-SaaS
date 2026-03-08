<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'business_name' => ['required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $tenant = Tenant::create([
            'name' => $validated['business_name'],
            'slug' => \Illuminate\Support\Str::slug($validated['business_name']),
        ]);

        $user->tenants()->attach($tenant->id, ['role' => 'owner']);

        // Set tenant context in session
        session(['tenant_id' => $tenant->id]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
