<?php

namespace Spatie\HttpLogger\Test;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Monolog\Handler\StreamHandler;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\HttpLogger\HttpLoggerServiceProvider;
use Spatie\HttpLogger\Middlewares\HttpLogger;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class TestCase extends Orchestra
{
    protected $uri = '/test-uri';

    protected function setUp(): void
    {
        parent::setUp();

        $this->initializeDirectory($this->getTempDirectory());

        $this->setUpRoutes();

        $this->setUpGlobalMiddleware();

        $this->setUpLog();
    }

    protected function tearDown(): void
    {
        if (File::isDirectory($this->getTempDirectory())) {
            File::deleteDirectory($this->getTempDirectory());
        }
    }

    protected function initializeDirectory($directory): void
    {
        if (File::isDirectory($directory)) {
            File::deleteDirectory($directory);
        }

        File::makeDirectory($directory);
    }

    protected function getTempDirectory($suffix = ''): string
    {
        return __DIR__ . '/temp' . ($suffix == '' ? '' : $this->uri . $suffix);
    }

    protected function getTempFile(): string
    {
        $path = $this->getTempDirectory() . '/test.md';

        File::put($path, 'Hello');
        return $path;
    }

    protected function getLogFile(): string
    {
        return $this->getTempDirectory() . '/http-logger.log';
    }

    protected function readLogFile(): string
    {
        return file_get_contents($this->getLogFile());
    }

    protected function getPackageProviders($app): array
    {
        return [HttpLoggerServiceProvider::class];
    }

    protected function setUpRoutes(): void
    {
        Route::get($this->uri, function () {
            return 'get';
        });

        Route::post($this->uri, function () {
            return 'post';
        });

        Route::put($this->uri, function () {
            return 'put';
        });

        Route::patch($this->uri, function () {
            return 'patch';
        });

        Route::delete($this->uri, function () {
            return 'delete';
        });
    }

    protected function setUpGlobalMiddleware(): void
    {
        $this->app[Kernel::class]->pushMiddleware(HttpLogger::class);
    }

    protected function setUpLog(): void
    {
        $this->app->configureMonologUsing(function ($monolog) {
            $monolog->pushHandler(new StreamHandler($this->getLogFile()));
        });
    }

    protected function makeRequest(
        string $method,
        string $uri,
        array $parameters = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ): Request {
        $files = array_merge($files, $this->extractFilesFromDataArray($parameters));

        return Request::createFromBase(
            SymfonyRequest::create(
                $this->prepareUrlForRequest($uri), $method, $parameters,
                $cookies, $files, array_replace($this->serverVariables, $server), $content
            )
        );
    }
}
