# Log HTTP requests

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-http-logger.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-http-logger)
[![Build Status](https://img.shields.io/travis/spatie/laravel-http-logger/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-http-logger)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/xxxxxxxxx.svg?style=flat-square)](https://insight.sensiolabs.com/projects/xxxxxxxxx)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-http-logger.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-http-logger)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-http-logger.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-http-logger)

This package adds a middleware which can log incoming requests to the default log. 
If anything goes wrong during a user's request, you'll still be able to access the original request data sent by that user.

This log acts as an extra safety net for critical user submissions, such as forms that generate leads.

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-http-logger
```

You can publish the config-file with:

```bash
php artisan vendor:publish --provider="Spatie\HttpLogger\HttpLoggerServiceProvider" --tag="config" 
```

This is the contents of the published config file:

```php
return [
    /**
     * The log profile used to log requests. A log profile implements the `LogProfile` class,
     * determines whether a request will be logged or not, and how the message is formatted.
     */
    'log_profile' => \Spatie\HttpLogger\DefaultLogger::class,

    /**
     * Filter out body fields which will never be logged.
     */
    'except' => [
        'password',
        'password_confirmation',
    ],
];
```

## Usage

This packages provides a middleware which can be added as a global middleware or to a single route.

```php
// As a middleware to a single route.

Route::get('/my-form', function () {
    //
})->middleware(\Spatie\HttpLogger\Middlewares\HttpLogger::class);
```

```php
// As a global middleware in `\App\Http\Kernel`.

protected $middleware = [
    // ...
    
    \Spatie\HttpLogger\Middlewares\HttpLogger::class
];
```

### Logging

A default log profile is added within this package. 
It will only log `POST`, `PUT`, `PATCH`, and `DELETE` requests.
It will write to the default Laravel logger.

You're free to implement your own log profile, and configure it in `config/http-logger.php`.

A custom log profile must implement the `\Spatie\HttpLogger\LogProfile` interface. 
This interface requires you to implement the `handleRequest` method.

```php
// Example implementation from `\Spatie\HttpLogger\DefaultLogProfile`

public function handleRequest(Request $request): void
{
    if (
        ! $request->isMethod('post')
        && ! $request->isMethod('put')
        && ! $request->isMethod('patch')
        && ! $request->isMethod('delete')
    ) {
        return;
    }

    $this->logger->info($this->createMessage($request));
}
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Brent Roose](https://github.com/brendt)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
