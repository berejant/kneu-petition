<?php

namespace Kneu\Petition\Policies;

use Kneu\Petition\User;
use Kneu\Petition\Petition;
use Illuminate\Auth\Access\HandlesAuthorization;

class PetitionPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can create petitions.
     *
     * @param  \Kneu\Petition\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the petition.
     *
     * @param  \Kneu\Petition\User  $user
     * @param  \Kneu\Petition\Petition  $petition
     * @return mixed
     */
    public function update(User $user, Petition $petition)
    {
        if($user->id === $petition->user_id) {
            if($petition->created_at->diffInMinutes() < config('petition.minutes_for_edit')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the petition.
     *
     * @param  \Kneu\Petition\User  $user
     * @param  \Kneu\Petition\Petition  $petition
     * @return mixed
     */
    public function delete(User $user, Petition $petition)
    {
        if($user->id === $petition->user_id) {
            if($petition->created_at->diffInMinutes() < config('petition.minutes_for_edit')) {
                return true;
            }
        }

        return false;
    }
}
