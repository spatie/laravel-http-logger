<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;

class LogRequests implements LogProfile
{
    public function shouldLogRequest(Request $request): bool
    {
        return in_array(strtolower($request->method()), config('http-logger.log_method'));
    }
}
