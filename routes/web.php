<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\BookingWizard;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\AppointmentController;
use App\Http\Controllers\Dashboard\ServiceController;
use App\Http\Controllers\Dashboard\StaffController;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\Dashboard\BusinessHourController;
use App\Http\Controllers\Dashboard\BillingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\TenantManagementController;
use App\Http\Controllers\Admin\UserManagementController;

// ─── Public ──────────────────────────────────────────────────
Route::get('/', function () { return redirect()->route('login'); });
Route::get('/book/{tenant:slug}', BookingWizard::class)->name('tenant.book');

// ─── Auth (Guests Only) ─────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// ─── Tenant Dashboard (Authenticated) ───────────────────────
Route::middleware(['auth', \App\Http\Middleware\SetTenantContext::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('appointments', AppointmentController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('staff', StaffController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('business-hours', BusinessHourController::class);

    Route::get('/billing', [BillingController::class, 'index'])->name('billing');
    Route::post('/billing/subscribe', [BillingController::class, 'subscribe'])->name('billing.subscribe');
    Route::post('/billing/portal', [BillingController::class, 'portal'])->name('billing.portal');
});

// ─── Admin Panel (Super Admin Only) ─────────────────────────
Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tenants', [TenantManagementController::class, 'index'])->name('tenants.index');
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
});
