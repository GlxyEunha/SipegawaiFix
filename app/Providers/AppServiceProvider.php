<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\GajiController;
use Illuminate\Support\Facades\View;

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
    public function boot()
    {
        View::composer('*', function ($view) {
            $jumlahKenaikanGaji = GajiController::getJumlahKenaikanGaji();
            $view->with('jumlahKenaikanGaji', $jumlahKenaikanGaji);
        });
    }
}
