<?php
use asbamboo\template\Template;
use asbamboo\framework\config\RouterConfig;

return [
    'kernel.router.config'     => [
        'class' => RouterConfig::class, 'init_params' => ['configs' => include __DIR__ . DIRECTORY_SEPARATOR . 'router.php']
    ],
    'kernel.template'   => [
        'class' => Template::class, 'init_params' => ['template_dir' => [dirname(__DIR__) . DIRECTORY_SEPARATOR . 'view']]
    ],
];

