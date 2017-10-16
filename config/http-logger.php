<?php

return [

    /*
     * The log profile used to log requests. A log profile implements the `LogProfile` class,
     * determines whether a request will be logged or not, and how the message is formatted.
     */
    'log_profile' => \Spatie\HttpLogger\DefaultLogProfile::class,

    /*
     * Filter out body fields which will never be logged.
     */
    'except' => [
        'password',
        'password_confirmation',
    ],
];
