<?php

namespace Melsaka\Voteable;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Melsaka\Voteable\Helpers\ModelRelations;
use Melsaka\Voteable\Models\Vote;

trait Voter
{
    use ModelRelations;

    public $voteMorph = 'voter';

    public function voteables(string $voteable): BelongsToMany
    {
        $table = config('voteable.table', 'votes');

        return $this->belongsToMany($voteable, $table, 'voter_id', 'voteable_id')->withPivot('vote', 'created_at', 'updated_at');
    }

    public function upVote($voteable): bool
    {
        return Vote::up($voteable, $this);
    }

    public function downVote($voteable): bool
    {
        return Vote::down($voteable, $this);
    }

    public function removeVote($voteable): bool
    {
        return Vote::remove($voteable, $this);
    }

    public function hasVote($voteable): bool
    {
        return Vote::has($voteable, $this);
    }
}
