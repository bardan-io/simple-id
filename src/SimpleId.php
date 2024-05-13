<?php

declare(strict_types=1);

namespace BardanIO\SimpleId;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Stringable;

final class SimpleId implements Arrayable, Stringable
{
    private readonly ?int $value;

    private readonly ?string $prefix;

    private readonly int $timestamp;

    private readonly int $random;

    public function __construct(?int $value, ?string $prefix, ?int $timestamp = null, ?int $random = null)
    {
        $this->value = $value;
        $this->prefix = $prefix;
        $this->timestamp = $timestamp ?? now()->getTimestampMs();
        $this->random = $random ?? random_int(0, PHP_INT_MAX);
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @param  class-string  $model
     */
    public static function make(string $model, ?int $value = null): self
    {
        return new self($value, SimpleIdRegistrar::getPrefixForModel($model));
    }

    public function toString(): string
    {
        return SimpleIdEncoder::encode($this);
    }

    public function query(): Builder
    {
        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = SimpleIdRegistrar::getModelForPrefix($this->prefix);

        return $model::query()->where([
            'uuid' => $this->__toString(),
        ]);
    }

    public function firstOrFail(): Model|Builder
    {
        return $this->query()->firstOrFail();
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function hasPrefix(): bool
    {
        return $this->prefix !== null;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function getDate(): Carbon
    {
        return Carbon::createFromTimestampMs($this->timestamp);
    }

    public function getRandom(): int
    {
        return $this->random;
    }

    public function toArray(): array
    {
        return [
            'random' => $this->random,
            'timestamp' => $this->timestamp,
            'value' => $this->value,
            'prefix' => $this->prefix,
        ];
    }
}
