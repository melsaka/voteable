<?php

namespace Melsaka\Voteable\Helpers;

use Illuminate\Database\Eloquent\Relations\MorphTo;

trait VoteRelations
{
    /**
     * Get the commentable model (ex: Post, Product, etc..).
     */
    public function voteable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the owner model (ex: User).
     */
    public function voter(): MorphTo
    {
        return $this->morphTo();
    }
}
