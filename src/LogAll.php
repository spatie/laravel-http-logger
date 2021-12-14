<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;

class LogAll implements LogProfile
{
    public function shouldLogRequest(Request $request): bool
    {
        return false;
    }

    public function shouldLogResponse(Request $request): bool
    {
        return false;
    }

    public function shouldLogRequestResponse(Request $request): bool
    {
        return true;
    }
}
