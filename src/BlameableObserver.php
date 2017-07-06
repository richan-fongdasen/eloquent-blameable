<?php

namespace RichanFongdasen\EloquentBlameable;

use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableService;

class BlameableObserver
{
    /**
     * Listening to any saving events triggered by the given Model
     *
     * @param  Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function saving(Model $model)
    {
        app(BlameableService::class)->updateAttributes($model);
    }
}
