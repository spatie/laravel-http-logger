<?php

use Spatie\HttpLogger\LogNonGetRequests;

beforeEach(function () {
    $this->logProfile = new LogNonGetRequests();
});

it('logs post patch put delete', function () {
    foreach (['post', 'put', 'patch', 'delete'] as $method) {
        $request = $this->makeRequest($method, $this->uri);

        $this->assertTrue($this->logProfile->shouldLogRequest($request), "{$method} should be logged.");
    }
});

it('doesnt log get head options trace', function () {
    foreach (['get', 'head', 'options', 'trace'] as $method) {
        $request = $this->makeRequest($method, $this->uri);

        $this->assertFalse($this->logProfile->shouldLogRequest($request), "{$method} should not be logged.");
    }
});

it('doesnt log when disabled', function () {
    config(['http-logger.enabled' => false]);

    foreach (['post', 'put', 'patch', 'delete'] as $method) {
        $request = $this->makeRequest($method, $this->uri);

        $this->assertFalse($this->logProfile->shouldLogRequest($request), "{$method} should not be logged.");
    }
});
