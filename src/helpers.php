<?php

use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableService;

if (!function_exists('blameable_user')) {
    /**
     * Get the blameable User identifier.
     *
     * @return mixed
     */
    function blameable_user(Model $model)
    {
        $user = \Auth::user();
        $userClass = app(BlameableService::class)->getConfiguration($model, 'user');

        if ($user instanceof $userClass) {
            return $user->getKey();
        }

        return null;
    }
}
