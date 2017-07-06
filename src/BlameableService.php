<?php

namespace RichanFongdasen\EloquentBlameable;

use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\Exceptions\UndefinedUserModelException;

class BlameableService
{
    /**
     * Get current configuration value for the given attributes.
     *
     * @param mixed  $config
     * @param string $key
     * @param string $default
     *
     * @return string
     */
    private function getConfiguration($config, $key, $default)
    {
        if (is_array($config) && array_key_exists($key, $config)) {
            return $config[$key];
        }

        return $default;
    }

    /**
     * Get Model's attribute name from blameable configuration for the given key.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     *
     * @return string
     */
    public function getAttributeName(Model $model, $key)
    {
        return $this->getConfiguration($model->blameable(), $key, $key);
    }

    /**
     * Get the User Model Class from blameable configuration.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     *
     * @throws RichanFongdasen\EloquentBlameable\Exceptions\UndefinedUserModelException
     *
     * @return string
     */
    public function getUserModel(Model $model)
    {
        $config = $model->blameable();
        $userModel = $this->getConfiguration($config, 'user', $config);

        if (!$userModel || is_array($userModel)) {
            throw new UndefinedUserModelException();
        }

        return $userModel;
    }

    /**
     * Set Model's attribute value for the given key.
     *
     * @param Model  $model
     * @param string $key
     * @param bool   $updateNeeded
     *
     * @return void
     */
    private function setAttribute(Model $model, $key, $updateNeeded = true)
    {
        $attribute = $this->getAttributeName($model, $key);

        if ($attribute && $updateNeeded) {
            $model->setAttribute($attribute, \Auth::user()->getAuthIdentifier());

            return $attribute;
        }

        return null;
    }

    /**
     * Update the blameable attributes of the given model.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function updateAttributes(Model $model)
    {
        $attributes = [];
        $attributes[] = $this->setAttribute($model, 'created_by', !$model->getKey());
        $attributes[] = $this->setAttribute($model, 'updated_by');

        return $model->isDirty($attributes);
    }
}
