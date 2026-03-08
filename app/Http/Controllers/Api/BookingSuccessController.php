<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Cashier\Cashier;

class BookingSuccessController extends Controller
{
    /**
     * Handle successful stripe checkout redirect.
     */
    public function success(Request $request): JsonResponse
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return response()->json(['error' => 'Missing session ID'], 400);
        }

        return response()->json([
            'message' => 'Payment successful and appointment confirmed!',
            'session_id' => $sessionId,
        ]);
    }

    /**
     * Handle canceled stripe checkout redirect.
     */
    public function cancel(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Payment was canceled. Your appointment is still pending and may be released.',
        ]);
    }
}
