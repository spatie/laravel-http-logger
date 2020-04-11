<?php

return [

    /*
     * The log profile which determines whether a request should be logged.
     * It should implement `LogProfile`.
     */
    'log_profile' => \Spatie\HttpLogger\LogNonGetRequests::class,

    /*
     * The log writer used to write the request to a log.
     * It should implement `LogWriter`.
     */
    'log_writer' => \Spatie\HttpLogger\DefaultLogWriter::class,

    /*
     * The log profile which determines whether a response should be logged.
     * It should implement `LogResponseProfile`.
     */
    'log_response_profile' => \Spatie\HttpLogger\LogNonGetRequestsResponses::class,

    /*
     * The log writer used to write the response to a log.
     * It should implement `LogResponseWriter`.
     */
    'log_response_writer' => \Spatie\HttpLogger\DefaultLogResponseWriter::class,

    /*
     * Filter out body fields which will never be logged.
     */
    'except' => [
        'password',
        'password_confirmation',
    ],

    /*
     * Filter out body response fields which will never be logged.
     */
    'except_reponse' => [
        'token',
    ],

];
