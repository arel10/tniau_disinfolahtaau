<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsAppOtpSender
{
    /**
     * Send OTP via WhatsApp.
     * Currently: log only (no real API needed).
     * Replace with actual WhatsApp API call when ready.
     */
    public function send(string $phone, string $message): bool
    {
        Log::info("[WA OTP] to {$phone}: {$message}");
        return true;
    }
}
