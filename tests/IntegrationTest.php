<?php

use function PHPUnit\Framework\assertFileExists;

it('logs an incoming request via the middleware', function () {
    $this->call('post', '/');

    assertFileExists($this->getLogFile());
});
