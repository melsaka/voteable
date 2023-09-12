<?php

namespace Melsaka\Voteable\Helpers;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Melsaka\Voteable\Models\Vote;

trait ModelRelations
{
    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, $this->voteMorph);
    }
}
