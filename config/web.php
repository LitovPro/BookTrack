<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'booktrack',
    'name' => 'BookTrack',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'bootstrap'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'booktrack-cookie-key-change-in-production',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'bootstrap' => [
            'class' => 'app\components\BootstrapComponent',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'books' => 'book/index',
                'books/create' => 'book/create',
                'books/<id:\d+>/update' => 'book/update',
                'books/<id:\d+>/delete' => 'book/delete',
                'books/<id:\d+>' => 'book/view',
                'authors' => 'author/index',
                'authors/create' => 'author/create',
                'authors/quick-create' => 'author/quick-create',
                'authors/<id:\d+>/update' => 'author/update',
                'authors/<id:\d+>/delete' => 'author/delete',
                'authors/<id:\d+>' => 'author/view',
                'report' => 'report/top-authors',
                'subscription' => 'subscription/index',
            ],
        ],
    ],
    'params' => $params,
    'container' => [
        'definitions' => [
            'app\services\SmsSenderInterface' => 'app\services\SmsPilotSender',
        ],
    ],
];

// Debug and Gii modules removed for production

return $config;
