<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tenant;
use App\Models\Service;
use App\Models\Staff;
use App\Services\Booking\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Appointment;

class BookingWizard extends Component
{
    public Tenant $tenant;
    
    public int $step = 1;

    // Step 1 Data
    public $services;
    public $staffMembers;
    public $service_id;
    public $staff_id;

    // Step 2 Data
    public $date;
    public $availableSlots = [];
    public $start_time; // 'YYYY-MM-DD HH:MM'

    // Step 3 Data
    public $first_name;
    public $last_name;
    public $email;
    public $phone;

    public function mount(Tenant $tenant)
    {
        $this->tenant = $tenant;
        
        // Set tenant context for global scopes
        session(['tenant_id' => $tenant->id]);
        
        $this->services = $tenant->services()->where('is_active', true)->get();
        
        // Date defaults to today
        $this->date = Carbon::now()->format('Y-m-d');
    }

    public function updatedServiceId($value)
    {
        if ($value) {
            $service = Service::find($value);
            // Get staff who provide this service
            $this->staffMembers = $service->staff()->where('is_active', true)->get();
            $this->staff_id = null;
        } else {
            $this->staffMembers = collect();
        }
    }

    public function updatedDate()
    {
        $this->loadSlots();
    }

    public function updatedStaffId()
    {
        $this->loadSlots();
    }

    public function loadSlots()
    {
        if ($this->service_id && $this->staff_id && $this->date) {
            $service = Service::find($this->service_id);
            $staff = Staff::find($this->staff_id);

            $availability = app(AvailabilityService::class);
            $this->availableSlots = $availability->getAvailableSlots($staff, $service, $this->date);
            $this->start_time = null; // Reset selection
        } else {
            $this->availableSlots = [];
        }
    }

    public function selectSlot($time)
    {
        $this->start_time = $this->date . ' ' . $time;
    }

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validate([
                'service_id' => 'required',
                'staff_id' => 'required',
            ]);
            $this->loadSlots();
        } elseif ($this->step === 2) {
            $this->validate([
                'start_time' => 'required',
            ]);
        }

        $this->step++;
    }

    public function previousStep()
    {
        $this->step--;
    }

    public function submit()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $service = Service::find($this->service_id);
        $staff = Staff::find($this->staff_id);

        $checkoutUrl = DB::transaction(function () use ($service, $staff) {
            // Verify slot again
            $availability = app(AvailabilityService::class);
            $slots = $availability->getAvailableSlots($staff, $service, $this->date);
            $timeOnly = Carbon::parse($this->start_time)->format('H:i');

            if (!in_array($timeOnly, $slots)) {
                $this->addError('start_time', 'This time slot was just booked by someone else.');
                return null;
            }

            // Customer
            $customer = Customer::firstOrCreate(
                ['tenant_id' => $this->tenant->id, 'email' => $this->email],
                [
                    'first_name' => $this->first_name,
                    'last_name' => $this->last_name,
                    'phone' => $this->phone,
                ]
            );

            // Appointment
            $end_time = Carbon::parse($this->start_time)->addMinutes($service->duration_minutes);

            $appointment = Appointment::create([
                'tenant_id' => $this->tenant->id,
                'customer_id' => $customer->id,
                'service_id' => $service->id,
                'staff_id' => $staff->id,
                'start_time' => $this->start_time,
                'end_time' => $end_time,
                'status' => 'pending',
            ]);

            \Illuminate\Support\Facades\Notification::route('mail', $customer->email)
                ->notify(new \App\Notifications\AppointmentConfirmed($appointment));

            if ($service->price > 0) {
                $checkout = $customer->checkoutCharge(
                    $service->price * 100,
                    $service->name,
                    1,
                    [
                        'success_url' => url('/api/booking/success?session_id={CHECKOUT_SESSION_ID}'),
                        'cancel_url' => url('/api/booking/cancel'),
                        'metadata' => ['appointment_id' => $appointment->id]
                    ]
                );
                return $checkout->url;
            }

            return null; // Free booking
        });

        if ($this->getErrorBag()->has('start_time')) {
            $this->step = 2; // Go back to pick a new slot
            return;
        }

        if ($checkoutUrl) {
            return redirect($checkoutUrl);
        }

        session()->flash('message', 'Appointment successfully booked!');
        $this->step = 4; // Success step
    }

    public function render()
    {
        return view('livewire.booking-wizard')->layout('layouts.guest');
    }
}
