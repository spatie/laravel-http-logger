<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;

class LogNonGetRequests implements LogProfile
{
    public function shouldLogRequest(Request $request): bool
    {
        return in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete']);
    }
}
