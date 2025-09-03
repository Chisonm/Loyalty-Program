<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\PurchaseMade;
use App\Observers\AchievementObserver;
use Illuminate\Queue\InteractsWithQueue;

final class ProcessAchievements
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct(private readonly AchievementObserver $achievementObserver)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PurchaseMade $event): void
    {
        $this->achievementObserver->checkAndUnlockAchievements($event->user);
    }
}
