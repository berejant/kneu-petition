<?php

namespace Kneu\Petition\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Kneu\Petition\Petition;
use Kneu\Petition\PetitionComment;
use Kneu\Petition\Policies\PetitionCommentPolicy;
use Kneu\Petition\Policies\PetitionPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Petition::class => PetitionPolicy::class,
        PetitionComment::class => PetitionCommentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
