<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Interfaces\ReservationRepositoryInterface::class,
            \App\Repositories\Eloquent\ReservationRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\AnnonceRepositoryInterface::class,
            \App\Repositories\Eloquent\AnnonceRepository::class
        );
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::policy(
            \App\Models\Reservations\Reservation::class,
            \App\Policies\ReservationPolicy::class
        );
        \Illuminate\Support\Facades\Gate::policy(
            \App\Models\Annonces\Annonce::class,
            \App\Policies\AnnoncePolicy::class
        );
    }
}
