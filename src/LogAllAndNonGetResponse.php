<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;

class LogAllAndNonGetResponse implements LogProfile
{
    public function shouldLogRequest(Request $request): bool
    {
        return !in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete']);
    }

    public function shouldLogResponse(Request $request): bool
    {
        return false;
    }

    public function shouldLogRequestResponse(Request $request): bool
    {
        return in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete']);
    }
}
