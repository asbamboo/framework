<?php
use asbamboo\router\Router;

return [
    'kernel.router' => [
        'class' => Router::class, 'init_params' => ['RouteCollection' => include __DIR__ . DIRECTORY_SEPARATOR . 'router.php']
    ],
];

