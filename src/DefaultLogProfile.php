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

    public function logRequest(Request $request)
    {
        $method = strtoupper($request->getMethod());
        $uri = $request->getPathInfo();
        $bodyAsJson = json_encode($request->except(config('http-logger.except')));
        $files = [];

        foreach ($request->files as $file) {
            $files[] .= $file->path();
        };

        $message = "{$method} {$uri} - Body: {$bodyAsJson} - Files: " . implode(', ', $files);

        Log::info($message);
    }
}
