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
     * Build blameable query scope.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed                                 $userId
     * @param string                                $key
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

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @param  string  $related
     * @param  string  $foreignKey
     * @param  string  $otherKey
     * @param  string  $relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    abstract public function belongsTo($related, $foreignKey = null, $otherKey = null, $relation = null);

    /**
     * Get the attributes that have been changed since last sync.
     *
     * @return array
     */
    abstract public function getDirty();

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    abstract public function getKeyName();

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    abstract public function getKey();

    /**
     * Get a new query builder that doesn't have any global scopes.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    abstract public function newQueryWithoutScopes();
}
