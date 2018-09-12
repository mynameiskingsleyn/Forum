<?php

namespace Forum\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //\View::composer('threads.create', function ($view) {
        //    $view->with('channels', \Forum\Channel::all());
        //  });
        //\View::share('channels', \Forum\Channel::all());

        // \View::composer('*', function ($view) {
        //     //var_dump('quering');
        //     $channels = \Cache::rememberForever('channels',function(){
        //       return Channel::all();
        //     })
        //     $view->with('channels', \Forum\Channel::all());
        // });
        //$channels = \Cache::pull('channels');
        \View::composer('*', function ($view) {
            //var_dump('quering');
            $channels = \Cache::remember('channels', 5, function () {
                return \Forum\Channel::all();
            });
            $view->with('channels', $channels);
        });
        // \View::composer('*', function ($view) {
        //     //var_dump('quering');
        //     $channels = \Cache::put('channels', function () {
        //         return \Forum\Channel::all();
        //     });
        //     $view->with('channels', $channels);
        // });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
