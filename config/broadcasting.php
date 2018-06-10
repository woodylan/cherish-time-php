<?php

return [
    'default' => env('BROADCAST_DRIVER', 'redis'),

    'connections' => [
        'redis' => [
            'driver'     => 'redis',
            'connection' => 'default',
        ],
    ],

];