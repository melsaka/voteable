<?php

namespace Melsaka\Voteable\Models;

use Melsaka\Voteable\Helpers\VoteRelations;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use VoteRelations;

    protected $casts = [
        'vote' => 'int',
        'voted' => 'bool',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getTable(): string
    {
        $tableName = config('voteable.table', 'votes');

        return $tableName;
    }

    public function scopeUp($query, $voteable, $voter): bool
    {
        return $this->action($voteable, $voter, 1);
    }

    public function scopeDown($query, $voteable, $voter): bool
    {
        return $this->action($voteable, $voter, -1);
    }

    public function scopeRemove($query, $voteable, $voter): bool
    {
        return $voteable->votes()->where($this->morphsArray($voter))->first()->delete();
    }

    public function scopeHas($query, $voteable, $voter): bool
    {
        return $voteable->votes()->where($this->morphsArray($voter))->exists();
    }

    private function action($voteable, $voter, $value): bool
    {
        $voterMorphs = $this->morphsArray($voter);
        
        $voted = $voteable->votes()->where($voterMorphs)->first();

        if ($voted) {
            return $voted->vote === $value ? $voted->delete() : $voted->update(['vote' => $value]); 
        }

        $voterMorphs['vote'] = $value;

        return (bool) $voteable->votes()->create($voterMorphs);
    }

    private function primaryId($model): int
    {
        return $model->getAttribute($model->primaryKey);
    }

    private function morphsArray($model): array
    {
        return [
            $model->voteMorph.'_id'   => $this->primaryId($model),
            $model->voteMorph.'_type' => get_class($model),
        ];
    }
}
