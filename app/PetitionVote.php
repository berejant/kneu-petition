<?php

namespace Kneu\Petition;

use Illuminate\Database\Eloquent\Model;

/**
 * Kneu\Petition\PetitionVote
 *
 * @property integer $id
 * @property integer $petition_id
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Kneu\Petition\Petition $petition
 * @property-read \Kneu\Petition\User $user
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\PetitionVote whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\PetitionVote wherePetitionId($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\PetitionVote whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\PetitionVote whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Kneu\Petition\PetitionVote whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PetitionVote extends Model
{

    public function petition()
    {
        return $this->belongsTo('Kneu\Petition\Petition');
    }

    public function user()
    {
        return $this->belongsTo('Kneu\Petition\User');
    }

    protected function finishSave(array $options = [])
    {
        parent::finishSave($options);
        $this->petition->calculateVotes();
    }

    public function delete()
    {
        $return = parent::delete();
        $this->petition->calculateVotes();
        return $return;
    }

}
