<?php

if (!function_exists('blameable_user')) {
    /**
     * Get the blameable User identifier.
     *
     * @return mixed
     */
    function blameable_user()
    {
        if ($user = \Auth::user()) {
            if (config('blameable.user') != 'App\User') {
                return (get_class($user) == config('blameable.user')) ? $user->getKey() : null;
            }

            return $user->getKey();
        }

        return null;
    }
}
