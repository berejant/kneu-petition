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
            $config = $app['config']['services.kneu'];

            // KNEU_REDIRECT_URI може бути не задано або зібрано як "${APP_URL}/login/complete"
            // з APP_URL, що закінчується на "/", тож прибираємо подвійні слеші у шляху
            $config['redirect'] = preg_replace('~(?<!:)/{2,}~', '/', $config['redirect'] ?: route('login.complete'));

            return Socialite::buildProvider(KneuProvider::class, $config);
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
