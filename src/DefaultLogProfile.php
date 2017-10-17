<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;

class DefaultLogProfile implements LogProfile
{
    public function shouldLogRequest(Request $request): bool
    {
        return in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete']);
    }
}
