<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface LogResponseProfile
{
    public function shouldLogResponse(Request $request, Response $response): bool;
}
