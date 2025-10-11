<?php

return [



    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],




    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'technician' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'rider' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'clinic' => [
            'driver' => 'session',
            'provider' => 'clinics',
        ],
        'staff' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],


    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'clinics' => [
            'driver' => 'eloquent',
            'model' => App\Models\Clinic::class,
        ],
    ],


    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'clinics' => [
            'provider' => 'clinics',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],



    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
