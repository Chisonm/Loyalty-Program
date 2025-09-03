<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class PaymentService
{
    public function processPayment(User $user, float $amount, string $type = 'purchase', array $metadata = []): array
    {
        $paymentData = [
            'id' => Str::uuid7(),
            'user_id' => $user->id,
            'amount' => $amount,
            'currency' => 'NGN',
            'reference' => ($type === 'cashback' ? 'cb-' : 'pay-').Str::uuid7(),
            'payment_method' => $type === 'cashback' ? 'cashback' : 'credit_card',
            'status' => 'completed',
            'processed_at' => now()->toISOString(),
            'metadata' => $metadata,
            'type' => $type,
        ];

        // save tranaction here
        $this->processTransaction($paymentData);
        Log::info("Processing payment with id: {$paymentData['id']}", $paymentData);

        return $paymentData;
    }

    public function processTransaction(array $paymentData): array
    {
        $transactionId = 'txn-'.Str::uuid7();
        $transaction = [
            'id' => Str::uuid7(),
            'transaction_id' => $transactionId,
            'amount' => $paymentData['amount'],
            'type' => $paymentData['type'],
            'currency' => $paymentData['currency'],
            'reference' => $paymentData['reference'],
            'payment_method' => $paymentData['payment_method'],
            'metadata' => json_encode($paymentData['metadata']),
            'user_id' => $paymentData['user_id'],
            'status' => $paymentData['status'],
            'description' => $this->getTransactionDescription($paymentData['type'], $paymentData['reference']),
            'processed_at' => $paymentData['processed_at'],
        ];

        Log::info("Processed transaction for user {$paymentData['user_id']}", [
            'transaction_id' => $transactionId,
            'user_id' => $paymentData['user_id'],
            'type' => $paymentData['type'],
            'amount' => $paymentData['amount'],
        ]);

        return $transaction;
    }

    private function getTransactionDescription(string $type, string $reference): string
    {
        return match ($type) {
            'cashback' => "Cashback payment - {$reference}",
            'purchase' => "Purchase payment - {$reference}",
            default => "Transaction - {$reference}"
        };
    }
}
