<?php

namespace src\Domain\Shared\Concerns;

use Illuminate\Database\Eloquent\Factories\Factory;

trait HasFactory
{

    public static function factory(callable|array|int|null $count = null, callable|array $state = []): Factory
    {
        $factory = static::newFactory() ?: Factory::factoryForModel(get_called_class());

        return $factory
            ->count(is_numeric($count) ? $count : null)
            ->state(is_callable($count) || is_array($count) ? $count : $state);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory<static>
     */
    protected static function newFactory(): Factory
    {
        $parts = str(get_called_class())->explode('\\');
        $domain = $parts[2];
        $model = $parts->last();

        return app("Database\\Factories\\{$domain}\\{$model}Factory");
    }
}
