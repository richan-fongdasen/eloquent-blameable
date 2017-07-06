<?php

namespace RichanFongdasen\EloquentBlameable;

use Illuminate\Database\Eloquent\Builder;
use RichanFongdasen\EloquentBlameable\BlameableObserver;

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
     * Get the user who created the record
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function creator()
    {
        return $this->belongsTo(
            app(BlameableService::class)->getUserModel($this),
            app(BlameableService::class)->getAttributeName($this, 'created_by')
        );
    }

    /**
     * Get the user who updated the record for the last time
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function updater()
    {
        return $this->belongsTo(
            app(BlameableService::class)->getUserModel($this),
            app(BlameableService::class)->getAttributeName($this, 'updated_by')
        );
    }

    public function scopeCreatedBy(Builder $query, $userId)
    {
        return $query->where(app(BlameableService::class)->getAttributeName($this, 'created_by'), $userId);
    }

    public function scopeUpdatedBy(Builder $query, $userId)
    {
        return $query->where(app(BlameableService::class)->getAttributeName($this, 'updated_by'), $userId);
    }

    /**
     * Define blameable configurations
     * Examples :
     *    return \App\User::class;
     *
     *    return [
     *        'created_by' => 'created_by',
     *        'updated_by' => 'updated_by',
     *        'user' => \App\User::class
     *    ];
     *
     * @return array
     */
    abstract public function blameable();
}
