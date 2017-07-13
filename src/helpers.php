<?php

if (!function_exists('blameable_user')) {
    /**
     * Get the blameable User identifier.
     *
     * @return mixed
     */
    function blameable_user()
    {
        return ($user = \Auth::user()) ? $user->getKey() : null;
    }
}
