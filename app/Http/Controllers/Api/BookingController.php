<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Tenant;
use App\Services\Booking\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    protected AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    /**
     * Get available time slots for a specific date and service.
     */
    public function getAvailability(Request $request): JsonResponse
    {
        $request->validate([
            'tenant' => 'required|string',
            'service_id' => 'required|exists:services,id',
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date_format:Y-m-d'
        ]);

        $tenant = Tenant::where('slug', $request->tenant)->firstOrFail();
        
        // Scope queries to the tenant manually since this is a public API route 
        // without auth middleware setting the Tenant context yet.
        $service = $tenant->services()->findOrFail($request->service_id);
        $staff = $tenant->staff()->findOrFail($request->staff_id);

        // Verify staff provides this service
        if (!$staff->services()->where('services.id', $service->id)->exists()) {
            return response()->json(['message' => 'This professional does not provide the selected service.'], 422);
        }

        $slots = $this->availabilityService->getAvailableSlots($staff, $service, $request->date);

        return response()->json([
            'date' => $request->date,
            'available_slots' => $slots,
        ]);
    }

    /**
     * Book an appointment.
     */
    public function book(Request $request): JsonResponse
    {
        $request->validate([
            'tenant' => 'required|string',
            'service_id' => 'required|exists:services,id',
            'staff_id' => 'required|exists:staff,id',
            'start_time' => 'required|date_format:Y-m-d H:i',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $tenant = Tenant::where('slug', $request->tenant)->firstOrFail();
        
        $service = $tenant->services()->findOrFail($request->service_id);
        $staff = $tenant->staff()->findOrFail($request->staff_id);

        // Verify staff provides this service
        if (!$staff->services()->where('services.id', $service->id)->exists()) {
            return response()->json(['message' => 'This professional does not provide the selected service.'], 422);
        }

        return DB::transaction(function () use ($tenant, $service, $staff, $request) {
            
            // Re-verify availability to prevent race conditions
            $dateOnly = Carbon::parse($request->start_time)->format('Y-m-d');
            $timeOnly = Carbon::parse($request->start_time)->format('H:i');
            $slots = $this->availabilityService->getAvailableSlots($staff, $service, $dateOnly);
            
            if (!in_array($timeOnly, $slots)) {
                return response()->json(['message' => 'This time slot is no longer available.'], 409);
            }

            // Create or update customer for this tenant
            $customer = Customer::firstOrCreate(
                ['tenant_id' => $tenant->id, 'email' => $request->email],
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                ]
            );

            // Create Appointment
            $end_time = Carbon::parse($request->start_time)->addMinutes($service->duration_minutes);

            $appointment = Appointment::create([
                'tenant_id' => $tenant->id,
                'customer_id' => $customer->id,
                'service_id' => $service->id,
                'staff_id' => $staff->id,
                'start_time' => $request->start_time,
                'end_time' => $end_time,
                'status' => 'pending', // Will become confirmed after email/payment
            ]);

            // Trigger Email Notification (Queued)
            \Illuminate\Support\Facades\Notification::route('mail', $customer->email)
                ->notify(new \App\Notifications\AppointmentConfirmed($appointment));

            if ($service->price > 0) {
                // Initialize Stripe Checkout session
                $checkout = $customer->checkoutCharge(
                    $service->price * 100, // Price in cents
                    $service->name,
                    1,
                    [
                        'success_url' => url('/api/booking/success?session_id={CHECKOUT_SESSION_ID}'),
                        'cancel_url' => url('/api/booking/cancel'),
                        'metadata' => [
                            'appointment_id' => $appointment->id,
                        ]
                    ]
                );

                return response()->json([
                    'message' => 'Redirecting to payment',
                    'appointment_id' => $appointment->id,
                    'checkout_url' => $checkout->url,
                ], 201);
            }

            return response()->json([
                'message' => 'Appointment successfully booked!',
                'appointment_id' => $appointment->id,
            ], 201);
        });
    }
}
