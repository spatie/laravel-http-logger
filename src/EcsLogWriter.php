<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;

// Elastic Common Schema format
// https://www.elastic.co/guide/en/ecs/current/ecs-http.html

class EcsLogWriter extends DefaultLogWriter implements LogWriter {
    public function logRequest(Request $request)
    {
        return false;
    }

    public function logResponse(Request $request, Response $response, ?float $time_taken)
    {
        return false;
    }

    public function logRequestResponse(Request $request, Response $response, ?float $time_taken)
    {
        $request_message = $this->getMessage($request);
        $response_message = $this->getResponseMessage($request, $response, $time_taken);

        $request_body = json_encode($request_message['body']);

        $data = [
            'http' => [
                'request' => [
                    'id' => $request_message['request_id'],
                    'method' => $request_message['method'],
                    'body' => [
                        'bytes' => strlen($request_body),
                        'content' => $request_body
                    ]
                ],

                'response' => [
                    'body' => [
                        'bytes' => strlen($response_message['content']),
                        'content' => $response_message['content']
                    ]
                ]
            ],
            'request' => $request_message,
            'response' => $response_message,
        ];

        Log::channel(config('http-logger.log_channel'))->info(null, $data);
    }
}


