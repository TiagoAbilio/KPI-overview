<?php

return [
    'oracle' => [
        'driver'         => 'oracle',
        'tns'            => env('DB_TNS', ''),
        'host'           => env('DB_HOST', 'deexbi05li.ffm.t-systems.com'),
        'port'           => env('DB_PORT', '1521'),
        'database'       => env('DB_DATABASE', 'SNOW_I36_LBUBRAZIL'),
        'service_name'   => env('DB_SERVICENAME', 'DMAP'),
        'username'       => env('DB_USERNAME', 'SNOW_I36_LBUBRAZIL_CONNECT'),
        'password'       => env('DB_PASSWORD', 'Fd6ZsiOmN4y7K6ew9RWl'),
        'charset'        => env('DB_CHARSET', 'AL32UTF8'),
        'prefix'         => env('DB_PREFIX', ''),
        'prefix_schema'  => env('DB_SCHEMA_PREFIX', ''),
        'edition'        => env('DB_EDITION', 'ora$base'),
        'server_version' => env('DB_SERVER_VERSION', '11g'),
        'load_balance'   => env('DB_LOAD_BALANCE', 'yes'),
        'dynamic'        => [],
    ],
];
