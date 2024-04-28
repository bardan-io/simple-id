<?php

namespace BardanIO\SimpleId;

use BardanIO\SimpleId\Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class HasSimpleIdTest extends TestCase
{
    /** @test */
    public function it_can_find_a_model_by_uuid()
    {
        $model = ModelWithoutPrefixedValue::create([
            'id' => 1,
        ]);

        $foundModel = ModelWithoutPrefixedValue::findUuid($model->uuid);

        $decoded = SimpleIdDecoder::decode($model->uuid);

        $this->assertEquals($model->id, $foundModel->id);
        $this->assertEquals('without', $decoded->getPrefix());
    }

    /** @test */
    public function it_can_find_a_model_by_uuid_with_prefix()
    {
        $model = ModelWithPrefixedValue::create([
            'id' => 1,
        ]);

        $foundModel = ModelWithPrefixedValue::findUuid($model->uuid);

        $this->assertEquals($model->id, $foundModel->id);

        $decoded = SimpleIdDecoder::decode($model->uuid);

        $this->assertStringStartsWith('10000', $decoded->getValue());
        $this->assertStringEndsWith($model->id, $decoded->getValue());

        $this->assertEquals('with', $decoded->getPrefix());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $simpleIdServiceProvider = new SimpleIdServiceProvider($this->app);

        $simpleIdServiceProvider->register();
        $registrar = $this->app->make(SimpleIdRegistrar::class);

        $registrar::registerModel('with', ModelWithPrefixedValue::class);
        $registrar::registerModel('without', ModelWithoutPrefixedValue::class);

        // Setup default database to use sqlite :memory:
        $this->app['config']->set('database.default', 'testbench');
        $this->app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        Schema::create('model_with_prefixed_values', function ($table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->timestamps();
        });

        Schema::create('model_without_prefixed_values', function ($table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->timestamps();
        });
    }
}

class ModelWithPrefixedValue extends Model
{
    use HasSimpleId;

    protected $guarded = [];

    public function getPrefixedValue(): int
    {
        return (int) ('10000' . $this->id);
    }
}

class ModelWithoutPrefixedValue extends Model
{
    use HasSimpleId;


    protected $guarded = [];
}
