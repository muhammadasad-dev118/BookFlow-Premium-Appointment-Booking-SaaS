<?php

namespace App\Services\Booking;

use App\Models\Staff;
use App\Models\Service;
use App\Models\Appointment;
use Carbon\Carbon;

class AvailabilityService
{
    /**
     * Get an array of formatted "H:i" open slots for a specific day.
     */
    public function getAvailableSlots(Staff $staff, Service $service, string $dateString): array
    {
        $date = Carbon::parse($dateString);

        // 1. Get the staff member's business hours for this day of the week
        // 0 = Sunday, 1 = Monday, 6 = Saturday (MySQL standard)
        // Carbon starts Monday as 1, Sunday as 0.
        $dayOfWeek = $date->dayOfWeek;

        $businessHour = $staff->businessHours()->where('day_of_week', $dayOfWeek)->first();

        // If no specific staff hours, fallback to tenant business hours
        if (!$businessHour) {
            $businessHour = $staff->tenant->businessHours()
                ->whereNull('staff_id')
                ->where('day_of_week', $dayOfWeek)
                ->first();
        }

        if (!$businessHour) {
            return []; // No business hours means no availability
        }

        // 2. Build Carbon instances for the working day boundaries
        $workStart = Carbon::parse($date->format('Y-m-d') . ' ' . $businessHour->start_time);
        $workEnd = Carbon::parse($date->format('Y-m-d') . ' ' . $businessHour->end_time);

        // 3. Get existing appointments for the day
        $existingAppointments = Appointment::where('staff_id', $staff->id)
            ->whereDate('start_time', $date->format('Y-m-d'))
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->get();

        // 4. Generate all raw slots based on 15 minute intervals (customizable)
        $rawSlots = [];
        $currentSlot = $workStart->copy();
        $intervalMinutes = 15; // Granularity

        // Ensure the slot + service duration doesn't exceed work day end
        while ($currentSlot->copy()->addMinutes($service->duration_minutes)->lte($workEnd)) {
            $rawSlots[] = $currentSlot->copy();
            $currentSlot->addMinutes($intervalMinutes);
        }

        // 5. Filter out slots that overlap with existing appointments
        $availableSlots = [];
        $duration = $service->duration_minutes;

        foreach ($rawSlots as $slot) {
            $proposedStart = $slot->copy();
            $proposedEnd = $slot->copy()->addMinutes($duration);

            $isOverlapping = false;

            foreach ($existingAppointments as $appointment) {
                // An overlap occurs if ProposedStart is strictly BEFORE ApptEnd 
                // AND ProposedEnd is strictly AFTER ApptStart.
                if ($proposedStart->lt($appointment->end_time) && $proposedEnd->gt($appointment->start_time)) {
                    $isOverlapping = true;
                    break;
                }
            }

            // Also check if slot is in the past (for today dates)
            if ($proposedStart->lt(Carbon::now())) {
                $isOverlapping = true;
            }

            if (!$isOverlapping) {
                $availableSlots[] = $slot->format('H:i');
            }
        }

        return $availableSlots;
    }
}
