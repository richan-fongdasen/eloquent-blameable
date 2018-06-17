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
    public function creating(Model $model)
    {
        app(BlameableService::class)->setAttribute($model, 'createdBy');
    }

    /**
     * Listening to any deleted events.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function deleted(Model $model)
    {
        app(BlameableService::class)->setAttribute($model, 'deletedBy');

        if ($model->useSoftDeletes()) {
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
    public function restoring(Model $model)
    {
        app(BlameableService::class)->setAttribute($model, 'deletedBy', true);
    }

    /**
     * Listening to any saving events.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function saving(Model $model)
    {
        app(BlameableService::class)->setAttribute($model, 'updatedBy');
    }
}
