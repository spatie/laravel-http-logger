<?php

namespace Spatie\HttpLogger\Test;

use Illuminate\Http\UploadedFile;
use Spatie\HttpLogger\DefaultLogWriter;

class DefaultLogWriterTest extends TestCase
{
    /** @var \Spatie\HttpLogger\DefaultLogWriter */
    protected $logger;

    public function setUp()
    {
        parent::setup();

        $this->logger = new DefaultLogWriter();
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
    public function it_will_log_the_body()
    {
        $request = $this->makeRequest('post', $this->uri, [
            'name' => 'Name',
        ]);

        $this->logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertContains('"name":"Name', $log);
    }

    /** @test */
    public function it_will_not_log_excluded_fields()
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
    public function it_logs_files()
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
