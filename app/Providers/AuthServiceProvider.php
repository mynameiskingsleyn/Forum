<?php

namespace Forum\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'Forum\Thread' => 'Forum\Policies\ThreadPolicy',
        'Forum\Reply' => 'Forum\Policies\ReplyPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user) {
            if ($user->name === 'John Doe') {
                return true;
            }
        });

        //
    }
}
