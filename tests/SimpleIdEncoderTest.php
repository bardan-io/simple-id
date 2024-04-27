<?php

namespace AchrafBardan\SimpleId\Tests;

use AchrafBardan\SimpleId\SimpleId;
use AchrafBardan\SimpleId\SimpleIdEncoder;

class SimpleIdEncoderTest extends TestCase
{
    public function testEncode()
    {
        $simpleId = new SimpleId(1, 'users');

        $encoded = SimpleIdEncoder::encode($simpleId);

        $this->assertIsString($encoded);
        $this->assertStringStartsWith('users_', $encoded);
    }

    protected function setUp(): void
    {
        parent::setUp();

        config(['simple-id.salt' => 'my-salt']);
    }
}
