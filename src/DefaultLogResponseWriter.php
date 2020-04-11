<?php

namespace Spatie\HttpLogger;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HtmlResponse;
use Illuminate\Http\JsonResponse;
use JsonSerializable;
use stdClass;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Symfony\Component\HttpFoundation\Response;

class DefaultLogResponseWriter implements LogResponseWriter
{
    public function logResponse(Request $request, Response $response)
    {
        $method = strtoupper($request->getMethod());

        $uri = $request->getPathInfo();

        $message = "{$method} {$uri} - ResponseBody: {}";

        try {
            if ($response instanceof Response) {
                if ($response->getStatusCode() == 200 ) {
                    if ($response instanceof JsonResponse) {
                        $body = json_encode($response->getData());
                    } else if ($response instanceof HtmlResponse) {
                        $body = $response->getContent();
                    } else {
                        $body = $response;
                    }

                    $message = "{$method} {$uri} - ResponseBody: {$body}";
                }
                else {
                    $message = "{$method} {$uri} - ResponseBodyCode: {$response->getStatusCode()}";
                }
            }
        }
        catch(Exception $e) {

        }

        Log::info($message);
    }
}
