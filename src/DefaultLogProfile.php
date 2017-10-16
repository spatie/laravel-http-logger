<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DefaultLogProfile implements LogProfile
{
    public function shouldLogRequest(Request $request): bool
    {
        return in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete']);
    }

    public function logRequest(Request $request)
    {
        $method = strtoupper($request->getMethod());

        $uri = $request->getPathInfo();

        $bodyAsJson = json_encode($request->except(config('http-logger.except')));

        $files = array_map(function(UploadedFile $file) {
            return $file->path();
        }, iterator_to_array($request->files));

        $message = "{$method} {$uri} - Body: {$bodyAsJson} - Files: " . implode(', ', $files);

        Log::info($message);
    }
}
