<?php

namespace App\Providers;

use App\Http\Composer;
use Illuminate\Support\ServiceProvider;
use App\Models\Tagihan;
use App\Observers\TagihanObserver;


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
         // Panggil fungsi register dari composer.php
        Composer::register();
        Tagihan::observe(TagihanObserver::class);
    }

    
}
