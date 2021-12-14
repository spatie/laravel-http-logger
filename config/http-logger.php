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
     * The log channel used to write the request.
     */
    'log_channel' => env('LOG_CHANNEL', 'stack'),

    /*
     * Add RequestID to each HTTP request for ease of finding relevant requests in log
     */
    'add_request_id' => false,

    /*
     * Expose RequestID as X-Request-ID HTTP response header
     */
    'expose_request_id' => false,

    /*
     * Log time taken for a request
     */
    'log_request_time' => true,

    /*
     * Filter out body fields which will never be logged.
     */
    'except' => [
        'password',
        'password_confirmation',
    ],

    /*
     * Filter out header fields which will never be logged.
     */
    'except_headers' => [
        'cookie',
        'authorization'
    ]
];
