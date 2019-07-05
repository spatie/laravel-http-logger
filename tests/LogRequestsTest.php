<?php

namespace Spatie\HttpLogger\Test;

use Spatie\HttpLogger\LogRequests;

class LogRequestsTest extends TestCase
{
    /** @var \Spatie\HttpLogger\LogNonGetRequests */
    protected $logProfile;

    public function setUp() : void
    {
        parent::setup();

        $this->logProfile = new LogRequests();
    }

    /** @test */
    public function it_logs_post_patch_put_delete()
    {
        foreach (['get', 'post', 'put', 'patch', 'delete'] as $method) {
            $request = $this->makeRequest($method, $this->uri);

            $this->assertTrue($this->logProfile->shouldLogRequest($request), "{$method} should be logged.");
        }
    }

    /** @test */
    public function it_doesnt_log_get_head_options_trace()
    {
        foreach (['head', 'options', 'trace'] as $method) {
            $request = $this->makeRequest($method, $this->uri);

            $this->assertFalse($this->logProfile->shouldLogRequest($request), "{$method} should not be logged.");
        }
    }
}
