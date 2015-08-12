<?php

return [
    'connections' => [
        'default' => [
            'driver'   => 'mysql',
            'host'     => getenv('DB_HOST') ?: 'localhost',
            'port'     => 3306,
            'user'     => getenv('DB_USER') ?: 'guest',
            'password' => getenv('DB_PASSWORD') ?: 'secret',
        ]
    ]
];