<?php

namespace Spatie\HttpLogger\Test;

use Illuminate\Http\UploadedFile;
use Spatie\HttpLogger\DefaultLogOutput;

class DefaultLogOutputTest extends TestCase
{
    /** @var \Spatie\HttpLogger\DefaultLogOutput */
    protected $logger;

    public function setUp()
    {
        parent::setup();

        $this->logger = new DefaultLogOutput();
    }

    /** @test */
    public function it_logs_request_method_and_uri()
    {
        foreach (['post', 'put', 'patch', 'delete'] as $method) {
            $request = $this->makeRequest($method, $this->uri);

            $this->logger->logRequest($request);
        }

        $log = $this->readLogFile();

        $this->assertContains("POST {$this->uri}", $log);
        $this->assertContains("PUT {$this->uri}", $log);
        $this->assertContains("PATCH {$this->uri}", $log);
        $this->assertContains("DELETE {$this->uri}", $log);
    }

    /** @test */
    public function the_body_is_logged()
    {
        $request = $this->makeRequest('post', $this->uri, [
            'name' => 'Name',
        ]);

        $this->logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertContains('"name":"Name', $log);
    }

    /** @test */
    public function excluded_fields_are_not_logged()
    {
        $request = $this->makeRequest('post', $this->uri, [
            'name' => 'Name',
            'password' => 'none',
            'password_confirmation' => 'none',
        ]);

        $this->logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertNotContains('password', $log);
        $this->assertNotContains('password_confirmation', $log);
    }

    /** @test */
    public function files_are_logged()
    {
        $file = $this->getTempFile();

        $request = $this->makeRequest('post', $this->uri, [], [], [
            'file' => new UploadedFile($file, 'test.md'),
        ]);

        $this->logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertContains('test.md', $log);
    }
}
