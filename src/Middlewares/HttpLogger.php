<?php

namespace Spatie\HttpLogger\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Spatie\HttpLogger\LogProfile;
use Spatie\HttpLogger\LogWriter;

class HttpLogger
{
    protected $logProfile;

    protected $logWriter;

    public function __construct(LogProfile $logProfile, LogWriter $logWriter)
    {
        $this->logProfile = $logProfile;
        $this->logWriter = $logWriter;
    }

    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    /**
     * Execute terminable actions after the response is returned.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Http\Response $response
     * @return void
     */
    public function terminate($request, $response): void
    {
        if ($this->logProfile->shouldLogRequest($request)) {
            $this->logWriter->logRequest($request);
        }

        if ($this->logProfile->shouldLogResponse($response)) {
            $this->logWriter->logResponse($response);
        }
    }

}
