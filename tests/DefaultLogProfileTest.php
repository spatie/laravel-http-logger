<?php

namespace Spatie\HttpLogger\Test;

use Spatie\HttpLogger\DefaultLogProfile;

class DefaultLogProfileTest extends TestCase
{
    /** @test */
    public function it_handles_a_post_request()
    {
        $request = $this->makePostRequest([
            'name' => 'Name'
        ]);

        $logger = $this->makeLogger();
        $logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertContains("POST {$this->uri}", $log);
    }

    /** @test */
    public function it_filters_except_fields_from_the_body()
    {
        $logger = $this->makeLogger();
        $request = $this->makePostRequest([
            'name' => 'Name',
            'password' => 'none',
            'password_confirmation' => 'none',
        ]);

        $logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertContains('"name":"Name"', $log);
        $this->assertNotContains('"password"', $log);
        $this->assertNotContains('"password_confirmation"', $log);
    }

    /** @test */
    public function it_doesnt_log_get_requests()
    {
        $logger = $this->makeLogger();
        $request = $this->makeGetRequest();

        $this->assertFalse($logger->shouldLogRequest($request));
    }

    private function makeLogger(): DefaultLogProfile
    {
        return $logger = new DefaultLogProfile();
    }
}
