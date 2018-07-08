<?php
use asbamboo\template\Template;
use asbamboo\framework\config\RouterConfig;
use asbamboo\framework\config\DbConfig;

return [
    'kernel.router.config'  => ['class' => RouterConfig::class, 'init_params' => ['configs' => include __DIR__ . DIRECTORY_SEPARATOR . 'router.php']],
    'kernel.db.config'      => ['class' => DbConfig::class, 'init_params' => ['configs' => include __DIR__ . DIRECTORY_SEPARATOR . 'db.php']],
    'kernel.template'       => ['class' => Template::class, 'init_params' => ['template_dir' => [dirname(__DIR__) . DIRECTORY_SEPARATOR . 'view']]],
];

