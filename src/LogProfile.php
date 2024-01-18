<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface LogProfile
{
    public function shouldLogRequest(Request $request): bool;

    public function shouldLogResponse(Response $response): bool;
}
