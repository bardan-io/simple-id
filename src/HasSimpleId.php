<?php

declare(strict_types=1);

namespace AchrafBardan\SimpleId;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Throwable;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasSimpleId
{
    protected const UUID_COLUMN = 'uuid';

    public static function findUuid(?string $uuid): ?self
    {
        return self::where(self::UUID_COLUMN, $uuid)->first();
    }

    public static function findUuidOrFail(?string $uuid): ?self
    {
        return self::where(self::UUID_COLUMN, $uuid)->firstOrFail();
    }


    public function getRouteKey()
    {
        return $this->getAttribute($this->getRouteKeyName());
    }

    public function getRouteKeyName(): string
    {
        return self::UUID_COLUMN;
    }

    public function getApiRouteKeyType(): string
    {
        return 'string';
    }

    public function decodedUuid(): Attribute
    {
        return new Attribute(
            get: fn () => SimpleIdDecoder::decode($this->getAttribute(self::UUID_COLUMN)),
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
                prefix: (new self)->getPrefixForModel(),
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

    public function getPrefixForModel(): ?string
    {
        return SimpleIdRegistrar::getPrefixForModel(static::class);
    }
}
