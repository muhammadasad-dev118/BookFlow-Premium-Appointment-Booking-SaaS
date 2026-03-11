<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index()
    {
        $tenant = auth()->user()->tenants()->first();
        $subscription = $tenant?->subscription('default');

        $plans = [
            [
                'name' => 'Basic',
                'price' => 29,
                'lookup_key' => config('services.stripe.plans.basic'),
                'features' => ['Up to 3 staff members', 'Unlimited appointments', 'Email reminders', 'Basic analytics'],
            ],
            [
                'name' => 'Pro',
                'price' => 79,
                'lookup_key' => config('services.stripe.plans.pro'),
                'features' => ['Unlimited staff members', 'Unlimited appointments', 'SMS & Email reminders', 'Advanced analytics', 'Priority support', 'Custom branding'],
            ],
        ];

        return view('dashboard.billing.index', compact('tenant', 'subscription', 'plans'));
    }

    public function subscribe(Request $request)
    {
        $tenant = auth()->user()->tenants()->first();
        $priceId = $request->input('price_id');
        
        if (!$priceId) {
            return back()->with('error', 'Invalid plan selected or plan not configured.');
        }

        try {
            return $tenant->newSubscription('default', $priceId)
                ->checkout([
                    'success_url' => route('billing') . '?success=1',
                    'cancel_url' => route('billing') . '?cancelled=1',
                ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Payment setup failed: ' . $e->getMessage());
        }
    }

    public function portal()
    {
        $tenant = auth()->user()->tenants()->first();

        if (!$tenant->stripe_id) {
            return back()->with('error', 'No billing account found.');
        }

        return $tenant->redirectToBillingPortal(route('billing'));
    }
}
