<?php

namespace Melsaka\Voteable;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Melsaka\Voteable\Helpers\ModelRelations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Melsaka\Voteable\Models\Vote;

trait Voteable
{
    use ModelRelations;

    public $voteMorph = 'voteable';

    public function voters(string $voters): BelongsToMany
    {
        $table = config('voteable.table', 'votes');

        return $this->belongsToMany($voters, $table, 'voteable_id', 'voter_id')->withPivot('vote', 'created_at', 'updated_at');
    }

    public function upVote($voter): bool
    {
        return Vote::up($this, $voter);
    }

    public function downVote($voter): bool
    {
        return Vote::down($this, $voter);
    }

    public function removeVote($voter): bool
    {
        return Vote::remove($this, $voter);
    }

    public function hasVote($voter): bool
    {
        return Vote::has($this, $voter);
    }

    public function scopeOrderByVotesCount($query, $direction = 'DESC'): Builder
    {
        return $query->withCount('votes as votes_count')->orderBy('votes_count', $direction);
    }

    public function scopeOrderByVotesSum($query, $direction = 'DESC'): Builder
    {
        return $query->withVotesSum()->orderBy('votes_sum', $direction);
    }

    public function scopeWithVoted($query, $voter): Builder
    {
        return $query->withCount(['votes as voted' => function ($q) use ($voter) {
            $q->where([
                $voter->voteMorph.'_id'   => $voter->getAttribute($voter->primaryKey),
                $voter->voteMorph.'_type' => get_class($voter),
            ])->limit(1);
        }]);
    }

    public function scopeWithVotesCount($query): Builder
    {
        return $query->withCount('votes');
    }
     
    public function scopeLoadVotesCount($query): Model
    {
        return $this->loadCount('votes');
    }

    public function scopeWithVotesSum($query): Builder
    {
        return $query->withCount(['votes as votes_sum' => function ($q) {
            $q->select(DB::raw('COALESCE(CAST(sum(vote) AS SIGNED), 0) as votes_sum'));
        }]);
    }
     
    public function scopeLoadVotesSum($query): Model
    {
        return $this->loadCount(['votes as votes_sum' => function ($query) {
            $query->select(DB::raw('COALESCE(CAST(sum(vote) AS SIGNED), 0) as votes_sum'));
        }]);
    }

    public function scopeWithUpVotesCount($query): Builder
    {
        return $query->withCount(['votes as up_votes_count' => function ($q) {
            $q->where('vote', 1);
        }]);
    }
     
    public function scopeLoadUpVotesCount($query): Model
    {
        return $this->loadCount(['votes as up_votes_count' => function ($q) {
            $q->where('vote', 1);
        }]);
    }

    public function scopeWithDownVotesCount($query): Builder
    {
        return $query->withCount(['votes as down_votes_count' => function ($q) {
            $q->where('vote', -1);
        }]);
    }
     
    public function scopeLoadDownVotesCount($query): Model
    {
        return $this->loadCount(['votes as down_votes_count' => function ($query) {
            $query->where('vote', -1);
        }]);
    }
}
