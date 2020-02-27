<?php

declare(strict_types=1);

namespace Atymic\DateIntervalCast\Cast;

use Atymic\DateIntervalCast\Exception\InvalidIsoDuration;
use Carbon\CarbonInterval;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class DateIntervalCast implements CastsAttributes
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string                              $key
     * @param mixed                               $value
     * @param array                               $attributes
     *
     * @throws InvalidIsoDuration
     *
     * @return \DateInterval|mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        try {
            return new \DateInterval($value);
        } catch (\Exception $e) {
            throw InvalidIsoDuration::make($value, $e);
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string                              $key
     * @param mixed                               $value
     * @param array                               $attributes
     *
     * @throws InvalidIsoDuration
     *
     * @return array|void
     */
    public function set($model, string $key, $value, array $attributes)
    {
        try {
            $value = is_string($value) ? CarbonInterval::create($value) : $value;

            return [$key => CarbonInterval::getDateIntervalSpec($value)];
        } catch (\Exception $e) {
            throw InvalidIsoDuration::make($value, $e);
        }
    }
}
