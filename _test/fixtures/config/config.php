<?php
use asbamboo\router\Router;
use asbamboo\template\Template;

return [
    'kernel.router'     => [
        'class' => Router::class, 'init_params' => ['RouteCollection' => include __DIR__ . DIRECTORY_SEPARATOR . 'router.php']
    ],
    'kernel.template'   => [
        'class' => Template::class, 'init_params' => ['template_dir' => [dirname(__DIR__) . DIRECTORY_SEPARATOR . 'view']]
    ],
];

