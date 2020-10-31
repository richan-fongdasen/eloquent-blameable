<?php

namespace RichanFongdasen\EloquentBlameable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

trait BlameableTrait
{
    /**
     * Get any of override 'blameable attributes'.
     *
     * @return array
     */
    public function blameable(): array
    {
        if (property_exists($this, 'blameable')) {
            return (array) static::$blameable;
        }

        return [];
    }

    /**
     * Boot the Blameable service by attaching
     * a new observer into the current model object.
     *
     * @return void
     */
    public static function bootBlameableTrait(): void
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
    private function buildBlameableScope(Builder $query, $userId, $key): Builder
    {
        if ($userId instanceof Model) {
            $userId = $userId->getKey();
        }

        return $query->where($this->getTable().'.'.app(BlameableService::class)->getConfiguration($this, $key), $userId);
    }

    /**
     * Get the user who created the record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            app(BlameableService::class)->getConfiguration($this, 'user'),
            app(BlameableService::class)->getConfiguration($this, 'createdBy')
        );
    }

    /**
     * Get the user who updated the record for the last time.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updater(): BelongsTo
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
    public function scopeCreatedBy(Builder $query, $userId): Builder
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
    public function scopeUpdatedBy(Builder $query, $userId): Builder
    {
        return $this->buildBlameableScope($query, $userId, 'updatedBy');
    }

    /**
     * Silently update the model without firing any
     * events.
     *
     * @return int
     */
    public function silentUpdate(): int
    {
        return $this->newQueryWithoutScopes()
            ->where($this->getKeyName(), $this->getKey())
            ->getQuery()
            ->update($this->getDirty());
    }

    /**
     * Confirm if the current model uses SoftDeletes.
     *
     * @return bool
     */
    public function useSoftDeletes(): bool
    {
        return in_array(SoftDeletes::class, class_uses_recursive($this), true);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @param string      $related
     * @param string|null $foreignKey
     * @param string|null $otherKey
     * @param string|null $relation
     *
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
