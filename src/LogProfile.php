<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;

interface LogProfile
{
    public function handleRequest(Request $request): void;
}
