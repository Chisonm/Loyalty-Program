<?php

declare(strict_types=1);

namespace App\Providers;

use App\Observers\AchievementObserver;
use App\Observers\BadgeObserver;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AchievementObserver::class);
        $this->app->singleton(BadgeObserver::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
