<?php

namespace AchrafBardan\SimpleId\Tests;

use AchrafBardan\SimpleId\SimpleId;
use AchrafBardan\SimpleId\SimpleIdDecoder;
use AchrafBardan\SimpleId\SimpleIdEncoder;

class SimpleIdDecoderTest extends TestCase
{
    public function testDecode()
    {
        $encoded = $this->encode(1, 'users');

        $decoded = SimpleIdDecoder::decode($encoded);

        $this->assertEquals(1, $decoded->getValue());
        $this->assertEquals('users', $decoded->getPrefix());
    }

    private function encode(int $id, string $prefix): string
    {
        return SimpleIdEncoder::encode(new SimpleId($id, $prefix));
    }

    protected function setUp(): void
    {
        parent::setUp();

        config(['simple-id.salt' => 'my-salt']);
    }
}
