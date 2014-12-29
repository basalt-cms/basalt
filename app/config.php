<?php

return [
    'database' => [
        'host' => 'localhost',
        'dbname' => 'basalt',
        'user' => 'root',
        'password' => '',
    ],
    'providers' => [
        'Basalt\\Providers\\AppServiceProvider',
        'Basalt\\Providers\\RouteServiceProvider',
        'Basalt\\Providers\\DatabaseServiceProvider',
        'Basalt\\Providers\\TwigServiceProvider',
    ]
];