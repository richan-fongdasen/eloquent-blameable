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
            return (get_class($user) == config('blameable.user')) ? $user->getKey() : null;
        }
        return null;
    }
}
