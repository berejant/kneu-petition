<?php

namespace Kneu\Petition\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Kneu\Petition\Petition;
use Kneu\Petition\PetitionComment;
use Kneu\Petition\Policies\PetitionCommentPolicy;
use Kneu\Petition\Policies\PetitionPolicy;
use Kneu\Petition\Socialite\KneuProvider;
use Laravel\Socialite\Facades\Socialite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::policy(Petition::class, PetitionPolicy::class);
        Gate::policy(PetitionComment::class, PetitionCommentPolicy::class);

        Paginator::useBootstrapThree();

        Socialite::extend('kneu', function ($app) {
            return Socialite::buildProvider(KneuProvider::class, $app['config']['services.kneu']);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        setlocale(LC_ALL, config('app.locale'), 'uk_UA', 'uk_UA.utf8');
        \Carbon\Carbon::setLocale(config('app.locale'));
    }
}
