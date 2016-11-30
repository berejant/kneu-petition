<?php

namespace Kneu\Petition;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Petition extends Model
{
    use SoftDeletes;

    protected $casts = [
        'is_closed' => 'boolean',
        'is_successful' => 'boolean',
        'votes' => 'int',
    ];

    protected $fillable = ['title', 'content'];

    public function user()
    {
        return $this->belongsTo('Kneu\Petition\User');
    }

    public function petitionVotes()
    {
        return $this->hasMany('Kneu\Petition\PetitionVote');
    }

    public function petitionComments()
    {
        return $this->hasMany('Kneu\Petition\PetitionComment');
    }

    public function getResolution()
    {
        if($this->is_successful) {
            return 'successful';
        }

        if($this->is_closed) {
            return 'closed';
        }

        return 'open';
    }

    public function getProgress($multiplier = 100)
    {
        $progress = $this->votes / config('petition.votes_count_for_success');
        $progress = floor( $progress * 100) / 100;
        $progress = min(1, $progress);

        return $progress * $multiplier;
    }

    public function hasUserVoted ($userId)
    {
        if(!$userId) {
            return false;
        }

        return (bool)$this->petitionVotes()->where('user_id', $userId)->count();
    }

    public function calculateVotes()
    {
        $this->votes = $this->petitionVotes()->count();
        $this->save();
    }

}
