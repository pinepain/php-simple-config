<?php

return [
    'connections' => [
        'default' => [
            'driver'  => 'memcache',
            'servers' => ['cs1', 'cs2'],
        ],
        'cs1' => [
            'host' => 'cs1.example.internal'
        ],
        'cs2' => [
            'host' => 'cs2.example.internal'
        ]
    ],
];