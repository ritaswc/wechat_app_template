<?php

$cache_config = [
    'class' => env('CACHE_CLASS', 'yii\caching\FileCache')
];

switch ($cache_config['class']) {
    case 'yii\caching\MemCache':
        $cache_config['servers'] = [
            [
                'host' => env('MEMCACHE_SERVER_HOST', '127.0.0.1'),
                'port' => env('MEMCACHE_SERVER_PORT', 11211),
                'weight' => 100,
            ],
        ];
        break;
    default:
        break;
}

return $cache_config;