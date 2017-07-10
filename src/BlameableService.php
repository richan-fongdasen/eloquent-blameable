<?php

namespace RichanFongdasen\EloquentBlameable;

use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\Exceptions\UndefinedUserModelException;

class BlameableService
{
    /**
     * Global configurations from config/blameable.php
     *
     * @var array
     */
    private $globalConfig;

    /**
     * Blameable Service Constructor
     */
    public function __construct()
    {
        $this->globalConfig = app('config')->get('blameable');
    }

    /**
     * Get configurations for the given Model
     *
     * @param  Illuminate\Database\Eloquent\Model  $model
     * @return array
     */
    private function getConfigurations(Model $model)
    {
        $modelConfigurations = method_exists($model, 'blameable') ?
            $model->blameable() : [];

        return array_merge($this->globalConfig, $modelConfigurations);
    }

    /**
     * Get current configuration value for the given attributes.
     *
     * @param Illuminate\Database\Eloquent\Model  $model
     * @param string $key
     * @return string
     */
    public function getConfiguration(Model $model, $key)
    {
        return data_get($this->getConfigurations($model), $key);
    }

    /**
     * Set Model's attribute value for the given key.
     *
     * @param Model  $model
     * @param string $key
     * @param bool   $updateNeeded
     * @return void
     */
    private function setAttribute(Model $model, $key, $updateNeeded = true)
    {
        $attribute = $this->getConfiguration($model, $key);

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
     * @return void
     */
    public function updateAttributes(Model $model)
    {
        $attributes = [];
        $attributes[] = $this->setAttribute($model, 'createdBy', !$model->getKey());
        $attributes[] = $this->setAttribute($model, 'updatedBy');

        return $model->isDirty($attributes);
    }
}
