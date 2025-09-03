<?php

declare(strict_types=1);

namespace App\Http\Controllers\APIs\Checkout;

use App\Http\Controllers\Controller;
use App\Services\PurchaseProcessingService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
final class CheckoutController extends Controller
{
    public function __construct(public PurchaseProcessingService $purchaseProcessingService) {}

    public function __invoke(): JsonResponse
    {
        $user = auth('sanctum')->user();
        $amount = 100;
        $purchase = $this->purchaseProcessingService->processPurchase($user, $amount, 'product purchase');

        return $this->successResponse([
            'purchase_id' => $purchase->id,
            'amount' => $purchase->amount,
            'status' => $purchase->status,
            'processed_at' => $purchase->processed_at,
            'current_badge' => $user->userStats?->currentBadge?->only(['key', 'name']),
            'current_achievements' => $user->achievements->map(fn ($achievement) => $achievement->only(['key', 'name']))->all(),
        ], 'Purchase successful', true, Response::HTTP_CREATED);
    }
}
