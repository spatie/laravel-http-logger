<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DefaultLogProfile implements LogProfile
{
    public function shouldLogRequest(Request $request): bool
    {
        return $request->isMethod('post')
            || $request->isMethod('put')
            || $request->isMethod('patch')
            || $request->isMethod('delete');
    }

    public function logRequest(Request $request): void
    {
        $method = strtoupper($request->getMethod());
        $uri = $request->getPathInfo();
        $bodyAsJson = json_encode($request->except(config('http-logger.except')));

        $message = "{$method} {$uri} - {$bodyAsJson}";

        Log::info($message);
    }
}
