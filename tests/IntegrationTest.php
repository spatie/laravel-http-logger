<?php

use function PHPUnit\Framework\assertFileDoesNotExist;
use function PHPUnit\Framework\assertFileExists;

it('logs an incoming request via the middleware', function () {
    $this->call('post', '/');

    assertFileExists($this->getLogFile());
});

it('doesnt log an incoming request when disabled', function () {
    config(['http-logger.enabled' => false]);

    $this->call('post', '/');

    assertFileDoesNotExist($this->getLogFile());
});
