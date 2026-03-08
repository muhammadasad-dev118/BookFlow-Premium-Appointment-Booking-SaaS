<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    /**
     * Handle incoming Stripe webhooks.
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        
        $secret = config('services.stripe.webhook_secret'); // Store STRIPE_WEBHOOK_SECRET in .env

        $event = null;

        try {
            // If secret is configured, verify signature
            if ($secret && $sigHeader) {
                $event = Webhook::constructEvent($payload, $sigHeader, $secret);
            } else {
                // For local dev without webhook secret
                $event = json_decode($payload, false);
            }
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the specific event we care about
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $appointmentId = $session->metadata->appointment_id ?? null;

            if ($appointmentId) {
                $appointment = Appointment::find($appointmentId);
                
                if ($appointment) {
                    $appointment->update(['status' => 'confirmed']);
                    Log::info("Appointment {$appointmentId} confirmed via Stripe webhook.");
                }
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}
