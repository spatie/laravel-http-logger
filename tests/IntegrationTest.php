<?php

namespace Spatie\HttpLogger\Test;

use function PHPUnit\Framework\assertFileExists;

test('it logs an incoming request via the middleware', function () {
    $this->call('post', '/');

    assertFileExists($this->getLogFile());
});
