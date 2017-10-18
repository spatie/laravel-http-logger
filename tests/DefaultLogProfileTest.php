<?php

namespace Spatie\HttpLogger\Test;

use Spatie\HttpLogger\DefaultLogProfile;

class DefaultLogProfileTest extends TestCase
{
    /** @var \Spatie\HttpLogger\DefaultLogProfile */
    protected $logProfile;

    public function setUp()
    {
        parent::setup();

        $this->logProfile = new DefaultLogProfile();
    }

    /** @test */
    public function it_logs_post_patch_put_delete()
    {
        foreach (['post', 'put', 'patch', 'delete'] as $method) {
            $request = $this->makeRequest($method, $this->uri);

            $this->assertTrue($this->logProfile->shouldLogRequest($request), "{$method} should be logged.");
        }
    }

    /** @test */
    public function it_doesnt_log_get_head_options_trace()
    {
        foreach (['get', 'head', 'options', 'trace'] as $method) {
            $request = $this->makeRequest($method, $this->uri);

            $this->assertFalse($this->logProfile->shouldLogRequest($request), "{$method} should not be logged.");
        }
    }
}
