<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface LogWriter
{
    public function logRequestResponse(Request $request, Response $response);
}
