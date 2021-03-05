# Laravel DateInterval / CarbonInterval Cast

[![Build Status](https://img.shields.io/github/workflow/status/atymic/laravel-dateinterval-cast/PHP?style=flat-square)](https://github.com/atymic/laravel-dateinterval-cast/actions) 
[![StyleCI](https://styleci.io/repos/243181977/shield)](https://styleci.io/repos/243181977) 
[![Latest Version on Packagist](https://img.shields.io/packagist/v/atymic/laravel-dateinterval-cast.svg?style=flat-square)](https://packagist.org/packages/atymic/laravel-dateinterval-cast) 
[![Total Downloads](https://img.shields.io/packagist/dt/atymic/laravel-dateinterval-cast.svg?style=flat-square)](https://packagist.org/packages/atymic/laravel-dateinterval-cast) 

Laravel has built-in casting for `date` & `datetime` types, but if you want to use ISO 8061 durations with the native
`DateInterval` class, or Carbon's `CarbonInterval` you're out of luck.

This package provides two custom casts (for `DateInterval` and `CarbonInterval` respectively) using Laravel 7.x/8.x's custom
casts feature.

## Installation

```bash
composer require atymic/laravel-dateinterval-cast
```

## Using this package

In your model's `$casts`, assign the property you wish to enable casting on to either of the casts provided by the package.
You should use a `varchar`/`string` field in your database table.

```php
class TestModel extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_xyz' => 'boolean',
        'date_interval' => DateIntervalCast::class,
        'carbon_interval' => CarbonIntervalCast::class,
    ];
}
```

The property on the model will then be cast to an interval object, and saved to the database as a ISO 8061 duration string.
If you try to assign an invalid duration (or the database table contains one, and you use a getter) an exception is thrown.


```php
$model = new TestModel();

$model->carbon_interval = now()->subHours(3)->diffAsCarbonInterval();

$model->save(); // Saved as `PT3H`
$model->fresh();

$model->carbon_interval; // Instance of `CarbonInterval`
$model->carbon_interval->forHumans(); // prints '3 hours ago'

try {
    $model->carbon_interval = 'not_a_iso_period'; 
} catch (\Atymic\DateIntervalCast\Exception\InvalidIsoDuration $e) {
    // Exception thrown if you try to assign an invalid duration
}
```

## Contributing

Contributions welcome :) 
Please create a PR and i'll review/merge it. 


## Licence
MIT
