<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class JsonLogWriter extends DefaultLogWriter implements LogWriter {
    public function logRequest(Request $request)
    {
        $data = $this->getMessage($request);
        $message = $this->formatMessage($data);

        Log::channel(config('http-logger.log_channel'))->info($message);
    }

    public function logResponse(Request $request, Response $response, ?float $time_taken)
    {
        $data = $this->getResponseMessage($request, $response, $time_taken);
        $message = $this->formatResponseMessage($data);

        Log::channel(config('http-logger.log_channel'))->info($message);
    }

    public function logRequestResponse(Request $request, Response $response, ?float $time_taken)
    {
        $request_message = $this->getMessage($request);
        $response_message = $this->getResponseMessage($request, $response, $time_taken);

        $message = $this->formatRequestResponseMessage(
            $request_message,
            $response_message,
            $time_taken
        );

        Log::channel(config('http-logger.log_channel'))->info($message);
    }

    protected function formatMessage(array $message)
    {
        return json_encode($message);
    }

    protected function formatResponseMessage(array $message) {
        return json_encode($message);
    }

    protected function formatRequestResponseMessage(array $request_message, array $response_message)
    {
        return json_encode([
            'kind' => 'request_response',
            'request' => $request_message,
            'response' => $response_message,
        ]);
    }
}

