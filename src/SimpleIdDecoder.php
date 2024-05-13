<?php

declare(strict_types=1);

namespace BardanIO\SimpleId;

use Hashids\Hashids;

/**
 * @method static \Illuminate\Database\Eloquent\Builder query(string $id)
 * @method static \Illuminate\Database\Eloquent\Model firstOrFail(string $id)
 */
final class SimpleIdDecoder
{
    public static function __callStatic(string $method, array $arguments)
    {
        return self::decode(...$arguments)->$method();
    }

    public static function decode(string $value): SimpleId
    {
        $prefix = null;

        if (strpos($value, '_')) {
            $parts = explode('_', $value);

            [$prefix, $value] = $parts;
        }

        $hashId = new Hashids(config('simple-id.salt'));

        $decoded = $hashId->decode($value);
        [$random, $timestamp] = $decoded;
        $value = $decoded[2] ?? null;

        return new SimpleId($value, $prefix, $timestamp, $random);
    }
}
