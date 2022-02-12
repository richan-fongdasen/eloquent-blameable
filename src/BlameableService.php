<?php

namespace RichanFongdasen\EloquentBlameable;

use Illuminate\Database\Eloquent\Model;

class BlameableService
{
    /**
     * Get configurations for the given Model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return string[]
     */
    private function getConfigurations(Model $model): array
    {
        $modelConfigurations = method_exists($model, 'blameable') ?
            $model->blameable() : [];

        return array_merge((array) config('blameable'), $modelConfigurations);
    }

    /**
     * Get current configuration value for the given attributes.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string                              $key
     *
     * @return string|null
     */
    public function getConfiguration(Model $model, string $key): ?string
    {
        $value = data_get($this->getConfigurations($model), $key);

        return is_string($value) ? $value : null;
    }

    /**
     * Set Model's attribute value for the given key.
     *
     * @param Model           $model
     * @param string          $key
     * @param int|string|null $userId
     *
     * @return bool
     */
    public function setAttribute(Model $model, string $key, $userId): bool
    {
        $attribute = $this->getConfiguration($model, $key);

        if ($attribute !== null) {
            $model->setAttribute($attribute, $userId);
        }

        return $model->isDirty($attribute);
    }
}
