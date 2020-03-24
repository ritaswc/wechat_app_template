<?php
/**
 * @copyright ©2018 浙江禾匠信息科技
 * @author Lu Wei
 * @link http://www.zjhejiang.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/7/12 10:05
 */

error_reporting(E_ERROR | E_PARSE);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = [
    'id' => 'basic',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'cookieValidationKey' => env('COOKIE_KEY', 'Ih9soAyN40yzKbWK8tiW275uCePneRw7'),
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'enabled' => env('LOG_ENABLED', false),
                    'levels' => explode(',', env('LOG_LEVELS', 'error')),
                    'logVars' => explode(',', env('LOG_VARS', '')),
                    'logFile' => env('LOG_FILE', '@runtime/logs/app.log'),
                ],
            ],
        ],
        'db' => null,
    ],
];

$app = new \yii\web\Application($config);
$app->run();