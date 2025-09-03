<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\PurchaseMade;
use App\Observers\BadgeObserver;
use App\Services\UserStatsService;

final class ProcessBadges
{
    /**
     * Create the event listener.
     */
    public function __construct(public readonly BadgeObserver $badgeObserver, public readonly UserStatsService $userStatsService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PurchaseMade $event): void
    {
        $this->userStatsService->updateStatsWhenPurchaseMade($event->user, $event->purchase);
        $this->badgeObserver->checkAndUnlockBadges($event->user);
    }
}
