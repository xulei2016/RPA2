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
    'uf20bak' => [
        'driver'         => 'oracle',
        'tns'            => env('OCI2_TNS', ''),
        'host'           => env('OCI2_HOST', ''),
        'port'           => env('OCI2_PORT', '1521'),
        'database'       => env('OCI2_DATABASE', ''),
        'username'       => env('OCI2_USERNAME', ''),
        'password'       => env('OCI2_PASSWORD', ''),
        'charset'        => env('OCI2_CHARSET', 'AL32UTF8'),
        'prefix'         => env('OCI2_PREFIX', ''),
        'prefix_schema'  => env('OCI2_SCHEMA_PREFIX', ''),
        'server_version' => env('OCI2_SERVER_VERSION', '11g'),
    ],
];
