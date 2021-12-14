<?php

namespace Spatie\HttpLogger\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Spatie\HttpLogger\LogProfile;
use Spatie\HttpLogger\LogWriter;
use Ramsey\Uuid\Uuid;

class HttpLogger
{
    protected $logProfile;

    protected $logWriter;

    public function __construct(LogProfile $logProfile, LogWriter $logWriter)
    {
        $this->logProfile = $logProfile;
        $this->logWriter = $logWriter;
    }

    public function addRequestId(Request $request)
    {
        $uuid = $request->headers->get('X-Request-ID');

        if (is_null($uuid)) {
            $uuid = Uuid::uuid4()->toString();
            $request->headers->set('X-Request-ID', $uuid);
        }

        $_SERVER['HTTP_X_REQUEST_ID'] = $uuid;
    }

    public function handle(Request $request, Closure $next)
    {
        if (config('http-logger.add_request_id')) {
            $this->addRequestId($request);
        }

        if ($this->logProfile->shouldLogRequest($request)) {
            $this->logWriter->logRequest($request);
        }

        if (config('http-logger.log_request_time')) {
            $time_start = microtime(true);
        } else {
            $time_taken = null;
        }

        $response = $next($request);

        if (config('http-logger.log_request_time')) {
            $time_end = microtime(true);
            $time_taken = $time_end - $time_start;
        }

        if (config('http-logger.expose_request_id')) {
            $uuid = $request->headers->get('X-Request-ID');
            $response->headers->set('X-Request-ID', $uuid);
        }

        if ($this->logProfile->shouldLogResponse($request)) {
            $this->logWriter->logResponse($request, $response, $time_taken);
        }

        if ($this->logProfile->shouldLogRequestResponse($request)) {
            $this->logWriter->logRequestResponse(
                $request,
                $response,
                $time_taken,
                $this->logProfile->shouldLogRequest($request),
                $this->logProfile->shouldLogResponse($request),
            );
        }

        return $response;
    }
}
