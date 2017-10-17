<?php

namespace Spatie\HttpLogger\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Spatie\HttpLogger\LogProfile;

class HttpLogger
{
    protected $logProfile;

    public function __construct(LogProfile $logProfile)
    {
        $this->logProfile = $logProfile;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->logProfile->shouldLogRequest($request)) {
            $this->logProfile->logRequest($request);
        }

        return $next($request);
    }
}
