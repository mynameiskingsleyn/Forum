<?php

namespace Forum\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Tests\HomeMadeFakes\FakeTrending;

class AppFakingProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        if (app()->environment('testing')) {
            app()->instance(\Forum\Classes\Trending::class, new FakeTrending());
        }
    }
}
