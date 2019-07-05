<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;

class LogRequests implements LogProfile
{
    public function shouldLogRequest(Request $request): bool
    {
        return in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete', 'get']);
    }
}
