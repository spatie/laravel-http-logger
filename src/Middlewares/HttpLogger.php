<?php

namespace Spatie\HttpLogger\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Spatie\HttpLogger\LogOutput;
use Spatie\HttpLogger\LogProfile;

class HttpLogger
{
    protected $logProfile;
    protected $logOutput;

    public function __construct(LogProfile $logProfile, LogOutput $logOutput)
    {
        $this->logProfile = $logProfile;
        $this->logOutput = $logOutput;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->logProfile->shouldLogRequest($request)) {
            $this->logOutput->logRequest($request);
        }

        return $next($request);
    }
}
