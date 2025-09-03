<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\PurchaseMade;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class PurchaseProcessingService
{
    public function __construct(private UserStatsService $userStatsService) {}

    public function processPurchase(User $user, float $amount, string $description): Purchase
    {
        return DB::transaction(function () use ($user, $amount, $description) {
            $purchase = $user->purchases()->create([
                'amount' => $amount,
                'status' => 'completed',
                'transaction_id' => 'trx-'.Str::uuid7(),
                'description' => $description,
                'processed_at' => now(),
            ]);

            $user->refresh();

            PurchaseMade::dispatch($user, $purchase);

            return $purchase;
        });
    }
}
