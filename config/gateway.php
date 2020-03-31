<?php

return [
    // Global parameters
    'global' => [
        'base_url' => 'https://doreming'
    ],

    // List of microservices behind the gateway
    'services' => [
        'account' => '22.22.22.22',
        'hr-service' => ''
    ],

    //Public Endpoints
    'endpoints' => [
        'account/v1/login' => '0.0.0.0',
        'test' => '0.0.0.1'
    ],

];
