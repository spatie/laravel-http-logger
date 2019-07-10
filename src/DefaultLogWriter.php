<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class DefaultLogWriter implements LogWriter
{
    public function logRequestResponse(Request $request, Response $response)
    {
        $method = strtoupper($request->getMethod());

        $uri = $request->getPathInfo();

        $bodyAsJson = json_encode($request->except(config('http-logger.except')));

        $files = array_map(function (UploadedFile $file) {
            return $file->getClientOriginalName();
        }, iterator_to_array($request->files));

        $message = "{$method} {$uri} - RequestBody: {$bodyAsJson} - Files: ".implode(', ', $files);

        if (config('http-logger.auth_user_id', false)) {
            $message .= "UserId: ".Auth::id()."-";
        }

        if (config('http-logger.log_response', false)) {
            $responseBodyAsJson = $response->getContent();
            $statusCode = $response->getStatusCode();
            $responseHeaderAsJson = json_encode($response->headers);

            $message .= "HttpStatus: $statusCode - ResponseBody: $responseBodyAsJson - Header: $responseHeaderAsJson";
        }

        Log::channel('httplogger')->info($message);
    }
}
