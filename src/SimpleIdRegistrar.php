<?php

declare(strict_types=1);

namespace AchrafBardan\SimpleId;

final class SimpleIdRegistrar
{
    /** @var array<string, class-string> */
    private static array $registeredModels = [];

    public static function registerModels(array $models): void
    {
        foreach ($models as $prefix => $model) {
            self::registerModel($prefix, $model);
        }
    }

    public static function registerModel(string $prefix, string $model): void
    {
        self::$registeredModels[$prefix] = $model;
    }

    public static function getPrefixForModel(string $model): ?string
    {
        $keyedModelClass = array_flip(self::$registeredModels);

        return $keyedModelClass[$model] ?? null;
    }

    public static function getModelForPrefix(string $prefix): ?string
    {
        return self::$registeredModels[$prefix] ?? null;
    }
}
