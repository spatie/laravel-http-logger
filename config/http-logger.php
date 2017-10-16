<?php

return [

    /*
     * The log profile used to log requests. A log profile determines wheter a request will be,
     * logged or not, and how the message is formatted. It should implement `LogProfile``.
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
