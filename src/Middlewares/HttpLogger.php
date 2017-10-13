<?php

namespace Spatie\HttpLogger\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Spatie\HttpLogger\LogProfile;

class HttpLogger
{
    private $logProfile;

    public function __construct(LogProfile $logProfile)
    {
        $this->logProfile = $logProfile;
    }

    public function handle(Request $request, Closure $next)
    {
        $this->logProfile->handleRequest($request);

        return $next($request);
    }
}
