<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel CORS
     |--------------------------------------------------------------------------
     |
     | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
     | to accept any value.
     |
     */

    'defaults' => [
      'supportsCredentials' => false,
      'allowedOrigins' => ['*'],
      'allowedHeaders' => ['*'],
      'allowedMethods' => ['*'],
      'exposedHeaders' => [],
      'maxAge' => 3600,
      'hosts' => []
    ],

    'paths' => [
        'api/*' => [
            'allowedOrigins' => array('*'),
            'allowedHeaders' => array('*'),
            'allowedMethods' => array('*'),
            'maxAge' => 3600
        ],
    ],
];

