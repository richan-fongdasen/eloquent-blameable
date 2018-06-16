<?php

namespace RichanFongdasen\EloquentBlameable;

use Illuminate\Database\Eloquent\Model;

class BlameableService
{
    /**
     * Global configurations from config/blameable.php.
     *
     * @var array
     */
    private $globalConfig;

    /**
     * Blameable Service Constructor.
     */
    public function __construct()
    {
        $this->globalConfig = app('config')->get('blameable');
    }

    /**
     * Get configurations for the given Model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
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
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string                              $key
     *
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
     * @param bool   $reset
     *
     * @return void
     */
    public function setAttribute(Model $model, $key, $reset = false)
    {
        $attribute = $this->getConfiguration($model, $key);

        if ($attribute) {
            $value = $reset ? null : blameable_user($model);
            $model->setAttribute($attribute, $value);
        }

        return $model->isDirty($attribute);
    }
}
