<?php
return [
    'a' => [
        'connection'    => [
            'driver'    => 'pdo_sqlite',
            'path'      =>  __DIR__ . '/db1.sqlite',
        ],'metadata'    => [
            'path'      => dirname(__DIR__) . '/model',
            'type'      => 'annotation',
        ],'is_dev'      => true,
    ],
    'b' => [
        'connection'    => [
            'driver'    => 'pdo_sqlite',
            'path'      =>  __DIR__ . '/db2.sqlite',
        ],'metadata'    => [
            'path'      => dirname(__DIR__) . '/model',
            'type'      => 'annotation',
        ],'is_dev'      => true,
    ],
];