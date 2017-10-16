<?php

namespace Spatie\HttpLogger\Test;

use Illuminate\Http\UploadedFile;
use Spatie\HttpLogger\DefaultLogProfile;

class DefaultLogProfileTest extends TestCase
{
    public function setUp()
    {
        parent::setup();


    }

    /** @test */
    public function it_logs_post_requests()
    {
        $request = $this->makeRequest('post', $this->uri, [
            'name' => 'Name'
        ]);

        $logger = $this->makeLogger();

        $logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertContains("POST {$this->uri}", $log);
    }

    /** @test */
    public function it_logs_patch_requests()
    {
        $request = $this->makeRequest('patch', $this->uri, [
            'name' => 'Name'
        ]);

        $logger = $this->makeLogger();
        $logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertContains("PATCH {$this->uri}", $log);
    }

    /** @test */
    public function it_logs_put_requests()
    {
        $request = $this->makeRequest('put', $this->uri, [
            'name' => 'Name'
        ]);

        $logger = $this->makeLogger();
        $logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertContains("PUT {$this->uri}", $log);
    }

    /** @test */
    public function it_logs_delete_requests()
    {
        $request = $this->makeRequest('delete', $this->uri);

        $logger = $this->makeLogger();
        $logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertContains("DELETE {$this->uri}", $log);
    }

    /** @test */
    public function it_doesnt_log_get_requests()
    {
        $logger = $this->makeLogger();
        $request = $this->makeRequest('get', $this->uri);

        $this->assertFalse($logger->shouldLogRequest($request));
    }

    /** @test */
    public function it_doesnt_log_head_requests()
    {
        $logger = $this->makeLogger();
        $request = $this->makeRequest('head', $this->uri);

        $this->assertFalse($logger->shouldLogRequest($request));
    }

    /** @test */
    public function it_doesnt_log_options_requests()
    {
        $logger = $this->makeLogger();
        $request = $this->makeRequest('options', $this->uri);

        $this->assertFalse($logger->shouldLogRequest($request));
    }

    /** @test */
    public function it_doesnt_log_trace_requests()
    {
        $logger = $this->makeLogger();
        $request = $this->makeRequest('trace', $this->uri);

        $this->assertFalse($logger->shouldLogRequest($request));
    }

    /** @test */
    public function the_body_is_logged()
    {
        $logger = $this->makeLogger();
        $request = $this->makeRequest('post', $this->uri, [
            'name' => 'Name',
        ]);

        $logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertContains('"name":"Name', $log);
    }

    /** @test */
    public function excluded_fields_are_not_logged()
    {
        $logger = $this->makeLogger();
        $request = $this->makeRequest('post', $this->uri, [
            'name' => 'Name',
            'password' => 'none',
            'password_confirmation' => 'none',
        ]);

        $logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertNotContains('"password"', $log);
        $this->assertNotContains('"password_confirmation"', $log);
    }

    /** @test */
    public function files_are_logged()
    {
        $logger = $this->makeLogger();
        $file = $this->getTempFile();
        $request = $this->makeRequest('post', $this->uri, [], [], [
            'file' => new UploadedFile($file, 'test.md'),
        ]);

        $logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertContains('test.md', $log);
    }

    private function makeLogger(): DefaultLogProfile
    {
        return $logger = new DefaultLogProfile();
    }
}
