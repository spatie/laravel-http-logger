<?php

namespace Spatie\HttpLogger\Test;

use Spatie\HttpLogger\LogRequests;

class LogRequestsTest extends TestCase
{
    /** @var \Spatie\HttpLogger\LogNonGetRequests */
    protected $logProfile;

    /** @var array of Http method should be logged */
    protected $methodShouldBeLogged;

    /** @var array of Http method should not be logged */
    protected $methodShouldNotBeLogged;

    public function setUp() : void
    {
        parent::setup();

        $this->logProfile = new LogRequests();

        $allMethod = ['get', 'post', 'put', 'patch', 'delete', 'head', 'options', 'trace'];

        $this->methodShouldBeLogged = config('http-logger.log_method');

        $this->methodShouldNotBeLogged = array_diff($allMethod, $this->methodShouldBeLogged);
    }

    /** @test */
    public function it_logs_post_patch_put_delete()
    {
        foreach ($this->methodShouldBeLogged as $method) {
            $request = $this->makeRequest($method, $this->uri);

            $this->assertTrue($this->logProfile->shouldLogRequest($request), "{$method} should be logged.");
        }
    }

    /** @test */
    public function it_doesnt_log_get_head_options_trace()
    {
        foreach ($this->methodShouldNotBeLogged as $method) {
            $request = $this->makeRequest($method, $this->uri);

            $this->assertFalse($this->logProfile->shouldLogRequest($request), "{$method} should not be logged.");
        }
    }
}
