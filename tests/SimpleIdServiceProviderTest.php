<?php

namespace BardanIO\SimpleId\Tests;

use BardanIO\SimpleId\SimpleIdRegistrar;
use BardanIO\SimpleId\SimpleIdServiceProvider;

class SimpleIdServiceProviderTest extends TestCase
{

    public function testRegisterModels()
    {
        $provider = new SimpleIdServiceProvider($this->app);

        $provider->boot();

        $this->assertEquals('App\Models\User', SimpleIdRegistrar::getModelForPrefix('users'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        config(['simple-id.models' => [
            'users' => 'App\Models\User',
        ]]);
    }
}
