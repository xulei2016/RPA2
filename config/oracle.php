<?php

return [
    'crm' => [
        'driver'         => 'oracle',
        'tns'            => env('OCI_TNS', ''),
        'host'           => env('OCI_HOST', ''),
        'port'           => env('OCI_PORT', '1521'),
        'database'       => env('OCI_DATABASE', ''),
        'username'       => env('OCI_USERNAME', ''),
        'password'       => env('OCI_PASSWORD', ''),
        'charset'        => env('OCI_CHARSET', 'AL32UTF8'),
        'prefix'         => env('OCI_PREFIX', ''),
        'prefix_schema'  => env('OCI_SCHEMA_PREFIX', ''),
        'server_version' => env('OCI_SERVER_VERSION', '11g'),
    ],
];
