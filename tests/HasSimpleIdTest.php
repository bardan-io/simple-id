<?php

namespace AchrafBardan\SimpleId\Tests;

use AchrafBardan\SimpleId\SimpleIdDecoder;
use AchrafBardan\SimpleId\Tests\Support\User;

class HasSimpleIdTest extends TestCase
{
    public function testFindUuid()
    {
        $model = new class() extends User {
            protected $table = 'users';
        };

        $model = $model::create();

        $found = $model::findUuid($model->uuid);

        $this->assertSame($model->uuid, $found->uuid);
    }

    public function testFindUuidOrFail()
    {
        $model = new class() extends User {
            protected $table = 'users';
        };

        $model = $model::create();

        $found = $model::findUuidOrFail($model->uuid);

        $this->assertSame($model->uuid, $found->uuid);
    }

    public function testGetRouteKey()
    {
        $model = new class() extends User {
            protected $table = 'users';
        };

        $model = $model::create();

        $this->assertSame($model->uuid, $model->getRouteKey());
    }

    public function testGetRouteKeyName()
    {
        $model = new class() extends User {
            protected $table = 'users';
        };

        $this->assertSame('uuid', $model->getRouteKeyName());
    }

    public function testGetApiRouteKeyType()
    {
        $model = new class() extends User {
            protected $table = 'users';
        };

        $this->assertSame('string', $model->getApiRouteKeyType());
    }

    public function testDecodedUuid()
    {
        $model = new class() extends User {
            protected $table = 'users';
        };

        $model = $model::create();

        $decoded = $model->decodedUuid;

        $this->assertEquals(SimpleIdDecoder::decode($model->uuid), $decoded);
    }

    public function testNewUuid()
    {
        $user = new User();

        $uuid = User::newUuid($user);

        $this->assertStringStartsWith('users_', $uuid);
    }
}
