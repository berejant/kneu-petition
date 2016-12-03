<?php

namespace Kneu\Petition;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Model implements AuthorizableContract, AuthenticatableContract
{
    use Authorizable;
    use Authenticatable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'email', 'first_name', 'middle_name', 'last_name', 'type', 'role',
    ];

    public function petitions()
    {
        return $this->hasMany('Kneu\Petition\Petition');
    }

    public function petitionVotes()
    {
        return $this->hasMany('Kneu\Petition\PetitionVote');
    }

    public function petitionComments()
    {
        return $this->hasMany('Kneu\Petition\PetitionComment');
    }

    public function setRememberToken($value)
    {
        // nothing
    }

    public function getRememberToken()
    {
        return null;
    }

    public function getName ()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function isSuperAdmin()
    {
        return 'admin' === $this->role;
    }
}
