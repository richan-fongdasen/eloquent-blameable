<?php

use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableService;

if (!function_exists('blameable_user')) {
    /**
     * Get the blameable User identifier.
     *
     * @param Model $model
     *
     * @return mixed
     */
    function blameable_user(Model $model)
    {
        $guard = app(BlameableService::class)->getConfiguration($model, 'guard');

        /** @var Model $user */
        $user = ($guard === null) ? \Auth::user() : \Auth::guard($guard)->user();
        $userClass = app(BlameableService::class)->getConfiguration($model, 'user');

        if ($user && $user instanceof $userClass) {
            return $user->getKey();
        }

        return null;
    }
}
