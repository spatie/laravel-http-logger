<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DefaultLogProfile implements LogProfile
{
    public function handleRequest(Request $request): void
    {
        if (
            ! $request->isMethod('post')
            && ! $request->isMethod('put')
            && ! $request->isMethod('patch')
            && ! $request->isMethod('delete')
        ) {
            return;
        }

        Log::info($this->createMessage($request));
    }

    protected function createMessage(Request $request): string
    {
        $method = strtoupper($request->getMethod());
        $uri = $request->getPathInfo();
        $bodyAsJson = json_encode($request->except(config('http-logger.except')));

        $message = "{$method} {$uri} - {$bodyAsJson}";

        return $message;
    }
}
