<?php

namespace BardanIO\SimpleId\Tests;

use BardanIO\SimpleId\SimpleIdServiceProvider;
use BardanIO\SimpleId\Tests\Support\User;
use Illuminate\Support\Facades\Schema;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /** setup */
    protected function setUp(): void
    {
        parent::setUp();

        // sqlite in-memory database
        config(['database.default' => 'testing']);
        config(['database.connections.testing' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]]);

        // load default config config/simple-id.php
        config(['simple-id' => require __DIR__ . '/../config/simple-id.php']);
        config(['simple-id.salt' => 'my-salt']);

        config([
            'simple-id.models' => [
                'users' => User::class,
            ]
        ]);

        Schema::create('users', function ($table) {
            $table->id();
            $table->string('uuid')->unique()->nullable();
            $table->timestamps();
        });

        // handle provider
        $provider = new SimpleIdServiceProvider($this->app);

        $provider->register();
        $provider->boot();

    }
}