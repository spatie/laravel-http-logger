<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Http\Response;
class DefaultLogWriter implements LogWriter
{
    protected $sanitizer;

    public function logRequest(Request $request)
    {
        $message = $this->formatMessage($this->getMessage($request));

        Log::channel(config('http-logger.log_channel'))->log(config('http-logger.log_level', 'info'), $message);
    }

    public function getMessage(Request $request)
    {
        $files = (new Collection(iterator_to_array($request->files)))
            ->map([$this, 'flatFiles'])
            ->flatten();

        return [
            'method' => strtoupper($request->getMethod()),
            'uri' => $request->getPathInfo(),
            'body' => $request->except(config('http-logger.except')),
            'headers' => $this->getSanitizer()->clean($request->headers->all(), config('http-logger.sanitize_headers')),
            'files' => $files,
        ];
    }

    public function logResponse(Response $response)
    {
        $message = $this->formatMessageResponse($this->getMessageResponse($response));
        Log::channel(config('http-logger.log_channel'))->log(config('http-logger.log_level', 'info'), $message);
    }

    public function getMessageResponse(Response $response)
    {
        return [
            'body' => $response->getContent(),
            'headers' => $this->getSanitizer()->clean($response->headers->all(), config('http-logger.sanitize_headers')),
            'code' => $response->status(),
        ];
    }

    protected function formatMessage(array $message)
    {
        $bodyAsJson = json_encode($message['body']);
        $headersAsJson = json_encode($message['headers']);
        $files = $message['files']->implode(',');

        return "{$message['method']} {$message['uri']} - Body: {$bodyAsJson} - Headers: {$headersAsJson} - Files: " . $files;
    }
    protected function formatMessageResponse(array $message)
    {
        $bodyAsJson = json_encode($message['body']);
        $headersAsJson = json_encode($message['headers']);

        return "{Response - Body: {$bodyAsJson} - Headers: {$headersAsJson} - Code: " . $message["code"];
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

    protected function getSanitizer()
    {
        if (! $this->sanitizer instanceof Sanitizer) {
            $this->sanitizer = new Sanitizer();
        }

        return $this->sanitizer;
    }
}
