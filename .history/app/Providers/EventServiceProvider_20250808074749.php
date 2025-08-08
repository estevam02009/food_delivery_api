<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Events\ReviewCreated;
use App\Listeners\UpdateAverageRating;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        ReviewCreated::class => [
            UpdateAverageRating::class,
        ],
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
