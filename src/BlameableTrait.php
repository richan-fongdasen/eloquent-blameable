<?php

namespace RichanFongdasen\EloquentBlameable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait BlameableTrait
{
    /**
     * Boot the Blameable service by attaching
     * a new observer into the current model object.
     *
     * @return void
     */
    public static function bootBlameableTrait()
    {
        static::observe(app(BlameableObserver::class));
    }

    /**
     * Build blameable query scope
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed                                 $userId
     * @param  string                                $key
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildBlameableScope(Builder $query, $userId, $key)
    {
        if ($userId instanceof Model) {
            $userId = $userId->getKey();
        }

        return $query->where(app(BlameableService::class)->getConfiguration($this, $key), $userId);
    }

    /**
     * Get the user who created the record.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function creator()
    {
        return $this->belongsTo(
            app(BlameableService::class)->getConfiguration($this, 'user'),
            app(BlameableService::class)->getConfiguration($this, 'createdBy')
        );
    }

    /**
     * Get the user who updated the record for the last time.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updater()
    {
        return $this->belongsTo(
            app(BlameableService::class)->getConfiguration($this, 'user'),
            app(BlameableService::class)->getConfiguration($this, 'updatedBy')
        );
    }

    /**
     * createdBy Query Scope.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed                                 $userId
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedBy(Builder $query, $userId)
    {
        return $this->buildBlameableScope($query, $userId, 'createdBy');
    }

    /**
     * updatedBy Query Scope.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed                                 $userId
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpdatedBy(Builder $query, $userId)
    {
        return $this->buildBlameableScope($query, $userId, 'updatedBy');
    }

    /**
     * Silently update the model without firing any
     * events.
     * 
     * @return void
     */
    public function silentUpdate()
    {
        $query = $this->newQueryWithoutScopes()->where($this->getKeyName(), $this->getKey());
        $dirty = $this->getDirty();

        if ($dirty) {
            $query->update($dirty);
        }
    }
}
