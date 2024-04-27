<?php

declare(strict_types=1);

namespace BardanIO\SimpleId;

use Hashids\Hashids;

final class SimpleIdEncoder
{
    public static function encode(SimpleId $SimpleId): string
    {
        $hashId = new Hashids(config('simple-id.salt'));

        $encoded = $hashId->encode(array_filter([
            $SimpleId->getRandom(),
            $SimpleId->getTimestamp(),
            $SimpleId->getValue(),
        ]));

        if (! $SimpleId->hasPrefix()) {
            return $encoded;
        }

        return $SimpleId->getPrefix() . '_' . $encoded;
    }
}
