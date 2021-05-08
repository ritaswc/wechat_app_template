<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => env('DB_DSN'),
    'username' => env('DB_USER'),
    'password' => env('DB_PASS'),
    'charset' => 'utf8',
    'tablePrefix' => env('DB_PREFIX', 'hjmall_'),
];
