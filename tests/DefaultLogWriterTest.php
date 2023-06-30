<?php

use Illuminate\Http\UploadedFile;

use function PHPUnit\Framework\assertStringContainsString;
use function PHPUnit\Framework\assertStringNotContainsString;

use Spatie\HttpLogger\DefaultLogWriter;

beforeEach(function () {
    $this->logger = new DefaultLogWriter();
});

it('logs request method and uri', function () {
    foreach (['post', 'put', 'patch', 'delete'] as $method) {
        $request = $this->makeRequest($method, $this->uri);

        $this->logger->logRequest($request);
    }

    $log = $this->readLogFile();

    assertStringContainsString("POST {$this->uri}", $log);
    assertStringContainsString("PUT {$this->uri}", $log);
    assertStringContainsString("PATCH {$this->uri}", $log);
    assertStringContainsString("DELETE {$this->uri}", $log);
});

it('will log the body', function () {
    $request = $this->makeRequest('post', $this->uri, [
        'name' => 'Name',
    ]);

    $this->logger->logRequest($request);

    $log = $this->readLogFile();

    assertStringContainsString('"name":"Name', $log);
});

it('will not log excluded fields', function () {
    $request = $this->makeRequest('post', $this->uri, [
        'name' => 'Name',
        'password' => 'none',
        'password_confirmation' => 'none',
    ]);

    $this->logger->logRequest($request);

    $log = $this->readLogFile();

    assertStringNotContainsString('password', $log);
    assertStringNotContainsString('password_confirmation', $log);
});

it('logs files', function () {
    $file = $this->getTempFile();

    $request = $this->makeRequest('post', $this->uri, [], [], [
        'file' => new UploadedFile($file, 'test.md'),
    ]);

    $this->logger->logRequest($request);

    $log = $this->readLogFile();

    assertStringContainsString('test.md', $log);
});

it('logs one file in an array', function () {
    $file = $this->getTempFile();

    $request = $this->makeRequest('post', $this->uri, [], [], [
        'files' => [
        new UploadedFile($file, 'test.md'),
        ],
    ]);

    $this->logger->logRequest($request);

    $log = $this->readLogFile();

    assertStringContainsString('test.md', $log);
});

it('logs multiple files in an array', function () {
    $file = $this->getTempFile();

    $request = $this->makeRequest('post', $this->uri, [], [], [
        'files' => [
        new UploadedFile($file, 'first.doc'),
        new UploadedFile($file, 'second.doc'),
        ],
    ]);

    $this->logger->logRequest($request);

    $log = $this->readLogFile();

    assertStringContainsString('first.doc', $log);
    assertStringContainsString('second.doc', $log);
});

it('logs using the default log level', function () {
    $request = $this->makeRequest('post', $this->uri, [
        'name' => 'Name',
    ]);

    $this->logger->logRequest($request);

    $log = $this->readLogFile();

    assertStringContainsString('testing.INFO', $log);
    assertStringContainsString('"name":"Name', $log);
});

it('logs using the configured log level', function () {
    config(['http-logger.log_level' => 'debug']);
    $request = $this->makeRequest('post', $this->uri, [
        'name' => 'Name',
    ]);

    $this->logger->logRequest($request);

    $log = $this->readLogFile();

    assertStringContainsString('testing.DEBUG', $log);
    assertStringContainsString('"name":"Name', $log);
});

it('will sanitize sensitive headers', function () {
    config(['http-logger.sanitize_headers' => ['Authorization']]);

    $request = $this->makeRequest('post', $this->uri, [], [], [], ['HTTP_Authorization' => 'Bearer {token}', 'HTTP_Api-Version' => '2.4.12']);

    $this->logger->logRequest($request);

    $log = $this->readLogFile();

    assertStringContainsString('"authorization":["****"]', $log);
    assertStringContainsString('"api-version":["2.4.12"]', $log);
});
