<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LogNonGetRequests implements LogProfile
{
    public function shouldLogRequest(Request $request): bool
    {
        return in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete']);
    }

    public function shouldLogResponse(Response $response): bool
    {
        return false;
    }

}
