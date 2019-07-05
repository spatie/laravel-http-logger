<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DefaultLogWriter implements LogWriter
{
    public function logRequestResponse(Request $request, $response)
    {
        $method = strtoupper($request->getMethod());

        $uri = $request->getPathInfo();

        $bodyAsJson = json_encode($request->except(config('http-logger.except')));

        $files = array_map(function (UploadedFile $file) {
            return $file->getClientOriginalName();
        }, iterator_to_array($request->files));

        $responseBodyAsJson = json_encode($response->getContent());
        $statusCode = $response->getStatusCode();
        $responseHeaderAsJson = json_encode($response->headers);

        $message = "{$method} {$uri} - RequestBody: {$bodyAsJson} - Files: ".implode(', ', $files);
        $message .= "HttpStatus: $statusCode - ResponseBody: $responseBodyAsJson - Header: $responseHeaderAsJson";

        Log::info($message);
    }
}
