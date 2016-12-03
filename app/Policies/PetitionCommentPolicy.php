<?php

namespace Kneu\Petition\Policies;

use Kneu\Petition\User;
use Kneu\Petition\PetitionComment;
use Illuminate\Auth\Access\HandlesAuthorization;

class PetitionCommentPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the petitionComment.
     *
     * @param  \Kneu\Petition\User  $user
     * @param  \Kneu\Petition\PetitionComment  $petitionComment
     * @return mixed
     */
    public function view(User $user, PetitionComment $petitionComment)
    {
        return true;
    }

    /**
     * Determine whether the user can create petitionComments.
     *
     * @param  \Kneu\Petition\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the petitionComment.
     *
     * @param  \Kneu\Petition\User  $user
     * @param  \Kneu\Petition\PetitionComment  $petitionComment
     * @return mixed
     */
    public function update(User $user, PetitionComment $petitionComment)
    {
        if($user->id === $petitionComment->user_id) {
            if($petitionComment->created_at->diffInMinutes() < config('petition.comment_minutes_for_edit')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the petitionComment.
     *
     * @param  \Kneu\Petition\User  $user
     * @param  \Kneu\Petition\PetitionComment  $petitionComment
     * @return mixed
     */
    public function delete(User $user, PetitionComment $petitionComment)
    {
        if($user->id === $petitionComment->user_id) {
            if($petitionComment->created_at->diffInMinutes() < config('petition.comment_minutes_for_edit')) {
                return true;
            }
        }

        return false;
    }
}
