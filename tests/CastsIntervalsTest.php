<?php

namespace Illuminate\Tests\Integration\Database;

use Atymic\DateIntervalCast\Cast\CarbonIntervalCast;
use Atymic\DateIntervalCast\Cast\DateIntervalCast;
use Atymic\DateIntervalCast\Exception\InvalidIsoDuration;
use Atymic\DateIntervalCast\Tests\TestCase;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;

/**
 * @group integration
 */
class TestCastsIntervals extends TestCase
{
    public function testDateIntervalCast()
    {
        $model = new TestEloquentModelWithCustomCasts();

        $model->date_interval = new \DateInterval('P1D');
        $this->assertInstanceOf(\DateInterval::class, $model->date_interval);
        $this->assertSame('P1D', $model->getAttributes()['date_interval']);

        $model->save();
        $model->fresh();

        $this->assertInstanceOf(\DateInterval::class, $model->date_interval);
        $this->assertDatabaseHas('test', ['id' => $model->id, 'date_interval' => 'P1D']);
    }

    public function testCarbonIntervalCast()
    {
        $model = new TestEloquentModelWithCustomCasts();

        $model->carbon_interval = new CarbonInterval('P4D');
        $this->assertInstanceOf(CarbonInterval::class, $model->carbon_interval);
        $this->assertSame('P4D', $model->getAttributes()['carbon_interval']);

        $model->save();
        $model->fresh();

        $this->assertInstanceOf(CarbonInterval::class, $model->carbon_interval);
        $this->assertDatabaseHas('test', ['id' => $model->id, 'carbon_interval' => 'P4D']);
    }

    public function testThrowsExceptionOnInvalidDateInterval()
    {
        $id = TestEloquentModelWithCustomCasts::query()->insertGetId(['carbon_interval' => 'XYZ']);

        $this->expectException(InvalidIsoDuration::class);
        $this->expectExceptionMessage('is not a valid ISO 8601 duration');

        $model = TestEloquentModelWithCustomCasts::find($id);

        // Calls magic getter
        $model->carbon_interval;
    }
}

class TestEloquentModelWithCustomCasts extends Model
{
    protected $table = 'test';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date_interval' => DateIntervalCast::class,
        'carbon_interval' => CarbonIntervalCast::class,
    ];
}
