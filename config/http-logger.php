<?php

return [
    'log_profile' => \Spatie\HttpLogger\DefaultLogger::class,

    'except' => [
        'password',
        'password_confirmation',
    ],
];
