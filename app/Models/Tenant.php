<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;

class Tenant extends Model
{
    use HasFactory, Billable;

    protected $guarded = [];

    public function users() { return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps(); }
    public function services() { return $this->hasMany(Service::class); }
    public function staff() { return $this->hasMany(Staff::class); }
    public function customers() { return $this->hasMany(Customer::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function businessHours() { return $this->hasMany(BusinessHour::class); }
    public function payments() { return $this->hasMany(Payment::class); }

    public function plan()
    {
        $subscription = $this->subscription('default');
        
        if (!$subscription || !$subscription->valid()) {
            return 'free';
        }

        return $subscription->stripe_price === config('services.stripe.plans.pro') ? 'pro' : 'basic';
    }

    public function canAddStaff(): bool
    {
        $plan = $this->plan();

        if ($plan === 'pro') {
            return true;
        }

        if ($plan === 'basic') {
            return $this->staff()->count() < 3;
        }

        // Free/No subscription
        return $this->staff()->count() < 1;
    }
}
