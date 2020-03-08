<?php

namespace RichanFongdasen\EloquentBlameable;

use Illuminate\Database\Eloquent\Model;

class BlameableObserver
{
    /**
     * Listening to any creating events.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function creating(Model $model): void
    {
        app(BlameableService::class)->setAttribute($model, 'createdBy', blameable_user($model));
    }

    /**
     * Listening to any deleted events.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function deleted(Model $model): void
    {
        app(BlameableService::class)->setAttribute($model, 'deletedBy', blameable_user($model));

        if (
            method_exists($model, 'useSoftDeletes') && method_exists($model, 'silentUpdate') &&
            $model->useSoftDeletes() && $model->isDirty()
        ) {
            $model->silentUpdate();
        }
    }

    /**
     * Listening to any restoring events.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function restoring(Model $model): void
    {
        app(BlameableService::class)->setAttribute($model, 'deletedBy', null);
    }

    /**
     * Listening to any saving events.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function saving(Model $model): void
    {
        app(BlameableService::class)->setAttribute($model, 'updatedBy', blameable_user($model));
    }
}
