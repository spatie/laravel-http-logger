
[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# Log HTTP requests

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-http-logger.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-http-logger)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/spatie/laravel-http-logger/run-tests?label=tests)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-http-logger.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-http-logger)

This package adds a middleware which can log incoming requests to the default log. 
If anything goes wrong during a user's request, you'll still be able to access the original request data sent by that user.

This log acts as an extra safety net for critical user submissions, such as forms that generate leads.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-http-logger.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-http-logger)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-http-logger
```

Optionally you can publish the config file with:

```bash
php artisan vendor:publish --provider="Spatie\HttpLogger\HttpLoggerServiceProvider" --tag="config" 
```

This is the contents of the published config file:

```php
return [

    /*
     * The log profile which determines whether a request should be logged.
     * It should implement `LogProfile`.
     */
    'log_profile' => \Spatie\HttpLogger\LogNonGetRequests::class,

    /*
     * The log writer used to write the request to a log.
     * It should implement `LogWriter`.
     */
    'log_writer' => \Spatie\HttpLogger\DefaultLogWriter::class,
    
    /*
     * The log channel used to write the request.
     */
    'log_channel' => env('LOG_CHANNEL', 'stack'),
    
    /*
     * The log level used to log the request.
     */
    'log_level' => 'info',
    
    /*
     * Filter out body fields which will never be logged.
     */
    'except' => [
        'password',
        'password_confirmation',
    ],
];
```

## Usage

This packages provides a middleware which can be added as a global middleware or as a single route.

```php
// in `app/Http/Kernel.php`

protected $middleware = [
    // ...
    
    \Spatie\HttpLogger\Middlewares\HttpLogger::class
];
```

```php
// in a routes file

Route::post('/submit-form', function () {
    //
})->middleware(\Spatie\HttpLogger\Middlewares\HttpLogger::class);
```

### Logging

Two classes are used to handle the logging of incoming requests: 
a `LogProfile` class will determine whether the request should be logged,
and `LogWriter` class will write the request to a log. 

A default log implementation is added within this package. 
It will only log `POST`, `PUT`, `PATCH`, and `DELETE` requests 
and it will write to the default Laravel logger.

You're free to implement your own log profile and/or log writer classes, 
and configure it in `config/http-logger.php`.

A custom log profile must implement `\Spatie\HttpLogger\LogProfile`. 
This interface requires you to implement `shouldLogRequest`.

```php
// Example implementation from `\Spatie\HttpLogger\LogNonGetRequests`

public function shouldLogRequest(Request $request): bool
{
   return in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete']);
}
```

A custom log writer must implement `\Spatie\HttpLogger\LogWriter`. 
This interface requires you to implement `logRequest`.

```php
// Example implementation from `\Spatie\HttpLogger\DefaultLogWriter`

public function logRequest(Request $request): void
{
    $method = strtoupper($request->getMethod());
    
    $uri = $request->getPathInfo();
    
    $bodyAsJson = json_encode($request->except(config('http-logger.except')));

    $message = "{$method} {$uri} - {$bodyAsJson}";

    Log::channel(config('http-logger.log_channel'))->info($message);
}
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

### Security

If you've found a bug regarding security please mail [security@spatie.be](mailto:security@spatie.be) instead of using the issue tracker.

## Credits

- [Brent Roose](https://github.com/brendt)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
