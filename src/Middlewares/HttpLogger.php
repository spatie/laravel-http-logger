<?php

namespace Spatie\HttpLogger\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\HttpLogger\LogWriter;
use Spatie\HttpLogger\LogProfile;
use Spatie\HttpLogger\LogResponseWriter;
use Spatie\HttpLogger\LogResponseProfile;

class HttpLogger
{
    protected $logProfile;
    protected $logWriter;

    public function __construct(LogProfile $logProfile, LogWriter $logWriter, logResponseProfile $logResponseProfile, logResponseWriter $logResponseWriter)
    {
        $this->logProfile = $logProfile;
        $this->logWriter = $logWriter;
        $this->logResponseProfile = $logResponseProfile;
        $this->logResponseWriter = $logResponseWriter;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->logProfile->shouldLogRequest($request)) {
            $this->logWriter->logRequest($request);
        }

        return $next($request);
    }

    public function terminate(Request $request, Response $response)
    {
        if ($this->logResponseProfile->shouldLogResponse($request, $response)) {
            $this->logResponseWriter->logResponse($request, $response);
        }

    }

}
