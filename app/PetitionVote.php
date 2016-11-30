<?php

namespace Kneu\Petition;

use Illuminate\Database\Eloquent\Model;

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
