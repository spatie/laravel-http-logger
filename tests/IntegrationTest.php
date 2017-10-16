<?php

namespace Spatie\HttpLogger\Test;

class IntegrationTest extends TestCase
{
    /** @test */
    public function it_logs_an_incoming_request_via_the_middleware()
    {
        $this->call('post', '/');

        $this->assertFileExists($this->getLogFile());
    }
}
