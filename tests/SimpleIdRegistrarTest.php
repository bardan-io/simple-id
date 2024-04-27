<?php

namespace AchrafBardan\SimpleId\Tests;

use AchrafBardan\SimpleId\SimpleIdRegistrar;
use PHPUnit\Framework\TestCase;

class SimpleIdRegistrarTest extends TestCase
{
    public function testRegisterModel()
    {
        $simpleIdRegistrar = new SimpleIdRegistrar();

        $simpleIdRegistrar::registerModel('users', 'App\Models\User');

        $this->assertEquals('App\Models\User', $simpleIdRegistrar::getModelForPrefix('users'));
    }

    public function testGetModelForPrefix()
    {
        $simpleIdRegistrar = new SimpleIdRegistrar();

        $simpleIdRegistrar::registerModels([
            'users' => 'App\Models\User',
        ]);

        $this->assertEquals('App\Models\User', $simpleIdRegistrar::getModelForPrefix('users'));
    }

    public function testGetPrefixForModel()
    {
        $simpleIdRegistrar = new SimpleIdRegistrar();

        $simpleIdRegistrar::registerModels([
            'users' => 'App\Models\User',
        ]);

        $this->assertEquals('users', $simpleIdRegistrar::getPrefixForModel('App\Models\User'));
    }

    public function testGetPrefixForModelReturnsNull()
    {
        $simpleIdRegistrar = new SimpleIdRegistrar();

        $simpleIdRegistrar::registerModels([
            'users' => 'App\Models\User',
        ]);

        $this->assertNull($simpleIdRegistrar::getPrefixForModel('App\Models\Profile'));
    }
}
