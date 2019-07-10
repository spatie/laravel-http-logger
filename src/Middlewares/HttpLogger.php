<?php

namespace Spatie\HttpLogger\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\HttpLogger\LogWriter;
use Spatie\HttpLogger\LogProfile;

class HttpLogger
{
    protected $logProfile;
    protected $logWriter;

    public function __construct(LogProfile $logProfile, LogWriter $logWriter)
    {
        $this->logProfile = $logProfile;
        $this->logWriter = $logWriter;
    }

    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response)
    {
        if ($this->logProfile->shouldLogRequest($request)) {
            $this->logWriter->logRequestResponse($request, $response);
        }
    }
}
