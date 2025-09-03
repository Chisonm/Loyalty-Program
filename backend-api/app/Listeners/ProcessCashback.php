<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\BadgeUnlocked;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Log;
use Throwable;

final class ProcessCashback
{
    private const CASHBACK_AMOUNT = 300;

    /**
     * Create the event listener.
     */
    public function __construct(private PaymentService $paymentService) {}

    /**
     * Handle the event.
     */
    public function handle(BadgeUnlocked $event): void
    {
        $user = $event->user;
        try {
            $this->paymentService->processPayment($user, (float) self::CASHBACK_AMOUNT, type: 'cashback', metadata: [
                'badge_key' => $event->badge->key,
                'badge_name' => $event->badge->name,
                'badge_id' => $event->badge->id,
                'cashback_reason' => 'badge_unlock',
                'description' => '₦'.self::CASHBACK_AMOUNT." cashback for unlocking {$event->badge->name} badge",
            ]);
            Log::info("Processed  Naira cashback for user {$user->id} after unlocking badge {$event->badge->name}", [
                'user_id' => $user->id,
                'badge_key' => $event->badge->key,
                'badge_id' => $event->badge->id,
                'amount' => self::CASHBACK_AMOUNT,
            ]);
        } catch (Throwable $e) {
            Log::error("Failed to process cashback for user {$user->id}", [
                'user_id' => $user->id,
                'badge_key' => $event->badge->key,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
