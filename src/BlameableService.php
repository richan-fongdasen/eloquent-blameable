<?php

namespace RichanFongdasen\EloquentBlameable;

use Illuminate\Database\Eloquent\Model;

class BlameableService
{
    /**
     * Global configurations from config/blameable.php.
     *
     * @var array<string>
     */
    private $globalConfig;

    /**
     * Blameable Service Constructor.
     */
    public function __construct()
    {
        $this->loadConfig();
    }

    /**
     * Get configurations for the given Model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array<string>
     */
    private function getConfigurations(Model $model) :array
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
     * @return mixed
     */
    public function getConfiguration(Model $model, string $key)
    {
        return data_get($this->getConfigurations($model), $key);
    }

    /**
     * Load default blameable configurations.
     *
     * @return void
     */
    public function loadConfig() :void
    {
        $this->globalConfig = app('config')->get('blameable');
    }

    /**
     * Set Model's attribute value for the given key.
     *
     * @param Model    $model
     * @param string   $key
     * @param int|null $userId
     *
     * @return bool
     */
    public function setAttribute(Model $model, string $key, $userId) :bool
    {
        $attribute = $this->getConfiguration($model, $key);

        if ($attribute !== null) {
            $model->setAttribute($attribute, $userId);
        }

        return $model->isDirty($attribute);
    }
}
