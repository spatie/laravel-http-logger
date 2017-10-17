<?php

return [

    /*
     * The log profile which determines whether a request should be logged.
     * It should implement `LogProfile`.
     */
    'log_profile' => \Spatie\HttpLogger\DefaultLogProfile::class,

    /**
     * The log profile used to log the actual request.
     * It should implement `LogOutput`.
     */
    'log_output' => \Spatie\HttpLogger\DefaultLogOutput::class,

    /*
     * Filter out body fields which will never be logged.
     */
    'except' => [
        'password',
        'password_confirmation',
    ],
];
