<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;

class LogNonGetRequests implements LogProfile
{
    public function shouldLogRequest(Request $request): bool
    {
        if (! config('http-logger.enabled')) {
            return false;
        }

        return in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete']);
    }
}
