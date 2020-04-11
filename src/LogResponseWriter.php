<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface LogResponseWriter
{
    public function logResponse(Request $request, Response $response);
}
