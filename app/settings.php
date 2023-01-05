<?php
return [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,

        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' => __DIR__ . '/../log/app.log',
        ],

        //mysql settings
        'db' => [
            'host' => 'localhost',
            'user' => 'root',
            'pass' => 'Inocas99.',
            'dbname' => 'api'
        ]
    ],
];