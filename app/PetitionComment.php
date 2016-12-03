<?php

namespace Kneu\Petition;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Kneu\Petition\PetitionComment
 *
 * @property integer $id
 * @property integer $petition_id
 * @property string $content
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \Kneu\Petition\Petition $petition
 * @property-read \Kneu\Petition\User $user
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\PetitionComment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\PetitionComment wherePetitionId($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\PetitionComment whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\PetitionComment whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\PetitionComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\PetitionComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\PetitionComment whereDeletedAt($value)
 * @mixin \Eloquent
 */
class PetitionComment extends Model
{
    use SoftDeletes;

    protected $fillable = ['content'];

    public function petition()
    {
        return $this->belongsTo('Kneu\Petition\Petition');
    }

    public function user()
    {
        return $this->belongsTo('Kneu\Petition\User');
    }

}
