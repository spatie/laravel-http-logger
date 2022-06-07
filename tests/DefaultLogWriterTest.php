<?php

namespace Spatie\HttpLogger\Test;

use Illuminate\Http\UploadedFile;
use Spatie\HttpLogger\DefaultLogWriter;

class DefaultLogWriterTest extends TestCase
{
    /** @var \Spatie\HttpLogger\DefaultLogWriter */
    protected $logger;

    public function setUp(): void
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

        $this->assertStringContainsString("POST {$this->uri}", $log);
        $this->assertStringContainsString("PUT {$this->uri}", $log);
        $this->assertStringContainsString("PATCH {$this->uri}", $log);
        $this->assertStringContainsString("DELETE {$this->uri}", $log);
    }

    /** @test */
    public function it_will_log_the_body()
    {
        $request = $this->makeRequest('post', $this->uri, [
            'name' => 'Name',
        ]);

        $this->logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertStringContainsString('"name":"Name', $log);
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

        $this->assertStringNotContainsString('password', $log);
        $this->assertStringNotContainsString('password_confirmation', $log);
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

        $this->assertStringContainsString('test.md', $log);
    }

    /** @test */
    public function it_logs_one_file_in_an_array()
    {
        $file = $this->getTempFile();

        $request = $this->makeRequest('post', $this->uri, [], [], [
            'files' => [
                new UploadedFile($file, 'test.md'),
            ],
        ]);

        $this->logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertStringContainsString('test.md', $log);
    }

    /** @test */
    public function it_logs_multiple_files_in_an_array()
    {
        $file = $this->getTempFile();

        $request = $this->makeRequest('post', $this->uri, [], [], [
            'files' => [
                new UploadedFile($file, 'first.doc'),
                new UploadedFile($file, 'second.doc'),
            ],
        ]);

        $this->logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertStringContainsString('first.doc', $log);
        $this->assertStringContainsString('second.doc', $log);
    }

    /** @test */
    public function it_logs_using_the_default_log_level()
    {
        $request = $this->makeRequest('post', $this->uri, [
            'name' => 'Name',
        ]);

        $this->logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertStringContainsString('testing.INFO', $log);
        $this->assertStringContainsString('"name":"Name', $log);
    }

    /** @test */
    public function it_logs_using_the_configured_log_level()
    {
        config(['http-logger.log_level' => 'debug']);
        $request = $this->makeRequest('post', $this->uri, [
            'name' => 'Name',
        ]);

        $this->logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertStringContainsString('testing.DEBUG', $log);
        $this->assertStringContainsString('"name":"Name', $log);
    }

    /** @test */
    public function it_logs_the_configured_message()
    {
        $message = 'Test Message';
        config(['http-logger.log_message' => $message]);
        $request = $this->makeRequest('post', $this->uri);

        $this->logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertStringContainsString($message, $log);
    }

    /** @test */
    public function it_logs_the_request_to_context()
    {
        $message = 'Test Message';
        config(['http-logger.log_message' => $message]);
        $request = $this->makeRequest('post', $this->uri, [
            'name' => 'Name',
        ]);

        $this->logger->logRequest($request);

        $log = $this->readLogFile();

        $this->assertStringContainsString($message, $log);
        $this->assertStringContainsString('"method":"POST"', $log);
        $this->assertStringContainsString('"body":{"name":"Name"}', $log);
    }
}
