<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Pagination\Paginator;

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
        Paginator::defaultView('vendor.pagination.custom');

        // Share Active Periode to all views
        try {
            if (\Schema::hasTable('tbl_periode')) {
                view()->share('globalActivePeriode', \App\Models\Periode::getActive());
                view()->share('globalPeriodes', \App\Models\Periode::orderBy('tahun', 'desc')->get());
            }
        } catch (\Exception $e) {
            // Silently fail during migrations
        }
    }
}
