<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;

class DefaultLogger implements LogProfile
{
    private $logger = null;
    private $except = [];

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->except = config('http-logger.except');
    }

    public function handleRequest(Request $request): void
    {
        if (
            ! $request->isMethod('post')
            && ! $request->isMethod('patch')
            && ! $request->isMethod('put')
            && ! $request->isMethod('delete')
        ) {
            return;
        }

        $this->logger->info($this->createMessage($request));
    }

    protected function createMessage(Request $request): string
    {
        $method = strtoupper($request->getMethod());
        $uri = $request->getPathInfo();
        $bodyAsJson = json_encode($request->except($this->except));

        $message = "{$method} {$uri} - {$bodyAsJson}";

        return $message;
    }
}
