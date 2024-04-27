<?php

declare(strict_types=1);

namespace BardanIO\SimpleId;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Throwable;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasSimpleId
{
    public static function findUuid(?string $uuid): ?self
    {
        return self::where('uuid', $uuid)->first();
    }

    public static function findUuidOrFail(?string $uuid): ?self
    {
        return self::where('uuid', $uuid)->firstOrFail();
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function getApiRouteKeyType(): string
    {
        return 'string';
    }

    public function decodedUuid(): Attribute
    {
        return new Attribute(
            get: fn ($value) => SimpleIdDecoder::decode($value),
        );
    }

    public static function newUuid(?Model $model = null): string
    {
        $model ??= new self();

        $value = method_exists($model, 'getPrefixedValue')
            ? $model->getPrefixedValue()
            : null;

        return SimpleIdEncoder::encode(
            new SimpleId(
                value: $value,
                prefix: SimpleIdRegistrar::getPrefixForModel(static::class),
            ),
        );
    }

    protected static function bootHasSimpleId(): void
    {
        static::creating(static function (Model $model): void {
            try {
                $uuid = $model->getAttribute($model->getRouteKeyName());
            } catch (Throwable) {
                $uuid = null;
            }

            if ($uuid !== null) {
                return;
            }

            self::setSimpleIdOnModel($model);
        });

        static::replicating(static fn (Model $model) => self::setSimpleIdOnModel($model));
    }

    private static function setSimpleIdOnModel(Model $model): void
    {
        $model->{$model->getRouteKeyName()} = self::newUuid($model);
    }
}
