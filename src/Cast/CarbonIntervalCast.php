<?php

declare(strict_types=1);

namespace Atymic\DateIntervalCast\Cast;

use Atymic\DateIntervalCast\Exception\InvalidIsoDuration;
use Carbon\CarbonInterval;

class CarbonIntervalCast extends DateIntervalCast
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string                              $key
     * @param mixed                               $value
     * @param array                               $attributes
     *
     * @return CarbonInterval|\DateInterval|mixed
     * @throws InvalidIsoDuration
     */
    public function get($model, string $key, $value, array $attributes)
    {
        try {
            return CarbonInterval::create($value);
        } catch (\Exception $e) {
            throw InvalidIsoDuration::make($value, $e);
        }
    }
}
