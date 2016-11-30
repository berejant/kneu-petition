<?php

namespace Kneu\Petition;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PetitionComment extends Model
{
    use SoftDeletes;

    public function petition()
    {
        return $this->belongsTo('Kneu\Petition\Petition');
    }

    public function user()
    {
        return $this->belongsTo('Kneu\Petition\User');
    }

}
