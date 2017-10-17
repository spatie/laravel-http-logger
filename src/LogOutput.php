<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;

interface LogOutput
{
    public function logRequest(Request $request);
}
