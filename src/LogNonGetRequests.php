<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LogNonGetRequests implements LogProfile
{
    public function shouldLogRequest(Request $request): bool
    {
        return in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete']);
    }

    public function shouldLogResponse(Response $response): bool
    {
        try {
            $content = $response->getContent();


            if ($content) {
                json_decode($content, false, 512, JSON_THROW_ON_ERROR);
            }
           return true;
        } catch (\JsonException $exception) {
           return false;
        }
    }

}
