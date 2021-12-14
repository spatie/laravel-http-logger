<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DefaultLogWriter implements LogWriter
{
    public function logRequest(Request $request)
    {
        $message = $this->formatMessage($this->getMessage($request));

        Log::channel(config('http-logger.log_channel'))->info($message);
    }

    public function logResponse(Request $request, $response, ?float $time_taken)
    {
        $message = $this->formatResponseMessage($this->getResponseMessage($request, $response, $time_taken));

        Log::channel(config('http-logger.log_channel'))->info($message);
    }

    public function logRequestResponse(Request $request, $response, ?float $time_taken)
    {
        $message = $this->formatRequestResponseMessage(
            $this->getMessage($request),
            $this->getResponseMessage($request, $response, $time_taken),
            $time_taken
        );

        Log::channel(config('http-logger.log_channel'))->info($message);
    }

    public function getMessage(Request $request)
    {
        $files = (new Collection(iterator_to_array($request->files)))
            ->map([$this, 'flatFiles'])
            ->flatten();

        $headers = $this->filterHeaders($request->headers->all());
        // This is not a real header, it's set by us.
        unset($headers['x-request-id']);

        $request_id = $request->headers->get('X-Request-ID');

        return [
            'kind' => 'request',
            'request_id' => $request_id,
            'method' => strtoupper($request->getMethod()),
            'uri' => $request->getPathInfo(),
            'body' => $this->filterBody($request->all()),
            'headers' => $headers,
            'files' => $files,
            'ip' => $request->getClientIp(),

        ];
    }
    protected function formatMessage(array $message)
    {
        $bodyAsJson = json_encode($message['body']);
        $headersAsJson = json_encode($message['headers']);
        $files = $message['files']->implode(',');

        $request_id = '';
        if ($message['request_id']) {
            $request_id = '{' . $message['request_id'] . '}';
        }

        return "{$request_id} {$message['ip']} {$message['method']} {$message['uri']} - Body: {$bodyAsJson} - Headers: {$headersAsJson} - Files: ".$files;
    }

    public function getResponseMessage(Request $request, $response, ?float $time_taken) {
        $request_id = $request->headers->get('X-Request-ID');
        $headers = $this->filterHeaders($response->headers->all());

        return [
            'kind' => 'response',
            'request_id' => $request_id,
            'status' => $response->status(),
            'content' => $response->getContent(),
            'headers' => $headers,
            'time_taken' => round($time_taken * 1000),
        ];
    }
    protected function formatResponseMessage(array $message)
    {
        $headersAsJson = json_encode($message['headers']);

        $request_id = '';
        if ($message['request_id']) {
            $request_id = '{' . $message['request_id'] . '} ';
        }

        $duration = '';
        if ($message['time_taken']) {
            $duration = $message['time_taken'] . 'ms ';
        }

        return "{$request_id}{$duration}Response {$message['status']}: {$message['content']} - Headers: {$headersAsJson}";
    }

    protected function formatRequestResponseMessage(array $request_message, array $response_message)
    {
        $requestMessage = $this->formatMessage($request_message);

        $headersAsJson = json_encode($response_message['headers']);

        $duration = '';
        if ($response_message['time_taken']) {
            $duration = $response_message['time_taken'] . 'ms ';
        }

        return "{$requestMessage} {$duration}Response: {$response_message['content']} - Headers: {$headersAsJson}";
    }

    protected function filterBody($body) {
        $except = config('http-logger.except');

        if ($except) {
            foreach($except as $h) {
                if (isset($body[$h])) {
                    $body[$h] = '[filtered]';
                }
            }
        }

        return $body;
    }

    protected function filterHeaders($headers) {
        $except_headers = config('http-logger.except_headers');

        if ($except_headers) {
            foreach($except_headers as $h) {
                if (isset($headers[$h])) {
                    $headers[$h] = '[filtered]';
                }
            }
        }

        return $headers;
    }

    public function flatFiles($file)
    {
        if ($file instanceof UploadedFile) {
            return $file->getClientOriginalName();
        }
        if (is_array($file)) {
            return array_map([$this, 'flatFiles'], $file);
        }

        return (string) $file;
    }
}
