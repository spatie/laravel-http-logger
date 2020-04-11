<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogNonGetRequestsResponses implements LogResponseProfile
{
    public function shouldLogResponse(Request $request, Response $response): bool
    {
        return in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete']);
    }
}
