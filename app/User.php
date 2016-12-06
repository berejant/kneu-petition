<?php

namespace Kneu\Petition;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;

/**
 * Kneu\Petition\User
 *
 * @property integer $id
 * @property string $email
 * @property string $type
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $role
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Kneu\Petition\Petition[] $petitions
 * @property-read \Illuminate\Database\Eloquent\Collection|\Kneu\Petition\PetitionVote[] $petitionVotes
 * @property-read \Illuminate\Database\Eloquent\Collection|\Kneu\Petition\PetitionComment[] $petitionComments
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\User whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\User whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\User whereMiddleName($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\User whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\User whereRole($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\User whereDeletedAt($value)
 * @mixin \Eloquent
 */
class User extends Model implements AuthorizableContract, AuthenticatableContract
{
    use Authorizable;
    use Authenticatable;

    public $timestamps = false;
    public $incrementing = false;

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
