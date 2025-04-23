<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\CuentaBancaria;
use App\Models\MovimientoBancario;
use App\Observers\MovimientoBancarioObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        MovimientoBancario::observe(MovimientoBancarioObserver::class);
    }
}
