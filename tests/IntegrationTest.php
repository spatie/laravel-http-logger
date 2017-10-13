<?php

namespace Spatie\HttpLogger\Test;

class IntegrationTest extends TestCase
{
    /** @test */
    public function it_logs_an_incoming_post_request()
    {
        $this->call('post', '/');

        $this->assertFileExists($this->getLogFile());
    }

    /** @test */
    public function it_logs_an_incoming_patch_request()
    {
        $this->call('patch', '/');

        $this->assertFileExists($this->getLogFile());
    }

    /** @test */
    public function it_logs_an_incoming_put_request()
    {
        $this->call('put', '/');

        $this->assertFileExists($this->getLogFile());
    }

    /** @test */
    public function it_logs_an_incoming_delete_request()
    {
        $this->call('delete', '/');

        $this->assertFileExists($this->getLogFile());
    }

    /** @test */
    public function it_doesnt_log_an_incoming_get_request()
    {
        $this->call('get', '/');

        $this->assertFileNotExists($this->getLogFile());
    }

    /** @test */
    public function it_doesnt_log_an_incoming_head_request()
    {
        $this->call('head', '/');

        $this->assertFileNotExists($this->getLogFile());
    }
}
