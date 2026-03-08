<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;

class AppointmentConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    protected Appointment $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $serviceName = $this->appointment->service->name;
        $date = $this->appointment->start_time->format('l, jS \\of F Y');
        $time = $this->appointment->start_time->format('g:i A');
        $tenantName = $this->appointment->tenant->name;

        return (new MailMessage)
            ->subject("Appointment Confirmed at {$tenantName}")
            ->greeting("Hello!")
            ->line("Your appointment for **{$serviceName}** has been successfully booked.")
            ->line("Date: **{$date}**")
            ->line("Time: **{$time}**")
            ->line("Store: **{$tenantName}**")
            ->line('Thank you for booking with us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'status' => 'confirmed'
        ];
    }
}
