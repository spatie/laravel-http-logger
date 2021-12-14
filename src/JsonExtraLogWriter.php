<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class JsonExtraLogWriter extends DefaultLogWriter implements LogWriter {
    public function logRequest(Request $request)
    {
        $data = $this->getMessage($request);
        Log::channel(config('http-logger.log_channel'))->info(null, $data);
    }

    public function logResponse(Request $request, Response $response, ?float $time_taken)
    {
        $data = $this->getResponseMessage($request, $response, $time_taken);
        Log::channel(config('http-logger.log_channel'))->info(null, $data);
    }

    public function logRequestResponse(Request $request, Response $response, ?float $time_taken)
    {
        $request_message = $this->getMessage($request);
        $response_message = $this->getResponseMessage($request, $response, $time_taken);

        $data = [
            'kind' => 'request_response',
            'request' => $request_message,
            'response' => $response_message,
        ];

        Log::channel(config('http-logger.log_channel'))->info(null, $data);
    }
}


