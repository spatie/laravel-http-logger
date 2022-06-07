<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Spatie\HttpLogger\LogWriter;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DefaultLogWriter implements LogWriter
{
    public function logRequest(Request $request)
    {
        $message = config('http-logger.log_message');
        if ($message === null) {
            $message = $this->formatMessage($this->getInfo($request));
            $context = [];
        } else {
            $context = $this->getInfo($request);
        }

        Log::channel(config('http-logger.log_channel'))->log(config('http-logger.log_level', 'info'), $message, $context);
    }

    public function getInfo(Request $request)
    {
        $files = (new Collection(iterator_to_array($request->files)))
            ->map([$this, 'flatFiles'])
            ->flatten();

        return [
            'method' => strtoupper($request->getMethod()),
            'uri' => $request->getPathInfo(),
            'body' => $request->except(config('http-logger.except')),
            'headers' => $request->headers->all(),
            'files' => $files,
        ];
    }

    protected function formatMessage(array $info)
    {
        $bodyAsJson = json_encode($info['body']);
        $headersAsJson = json_encode($info['headers']);
        $files = $info['files']->implode(',');

        return "{$info['method']} {$info['uri']} - Body: {$bodyAsJson} - Headers: {$headersAsJson} - Files: " . $files;
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
