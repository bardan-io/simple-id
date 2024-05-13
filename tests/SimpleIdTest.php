<?php

namespace BardanIO\SimpleId\Tests;

use BardanIO\SimpleId\SimpleId;
use BardanIO\SimpleId\SimpleIdEncoder;

class SimpleIdTest extends TestCase
{
    public function testGetValue()
    {
        $simpleId = new SimpleId(1, 'users');

        $this->assertEquals(1, $simpleId->getValue());
    }

    public function testGetPrefix()
    {
        $simpleId = new SimpleId(1, 'users');

        $this->assertEquals('users', $simpleId->getPrefix());
    }

    public function testToString()
    {
        $simpleId = new SimpleId(1, 'users');

        $this->assertEquals(SimpleIdEncoder::encode($simpleId), $simpleId->toString());
    }

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'simple-id.salt' => 'my-salt',
            'simple-id.models' => [
                'users' => 'App\Models\User',
            ]
        ]);
    }
}
