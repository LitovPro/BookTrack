<?php
// Перенаправление в web-директорию или отображение информации о проекте
if (php_sapi_name() !== 'cli') {
    $projectInfo = [
        'name' => 'BookTrack',
        'description' => 'Современная система управления библиотекой книг и авторов',
        'version' => '1.0.0',
        'github' => 'https://github.com/LitovPro/BookTrack',
        'demo' => 'web/index.php'
    ];

    echo "<!DOCTYPE html>
    <html lang='ru'>
    <head>
        <meta charset='UTF-8'>
        <title>{$projectInfo['name']} - Библиотечная система</title>
        <style>
            body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
            h1 { color: #333; }
            .info { background: #f4f4f4; padding: 15px; border-radius: 5px; }
            .links { margin-top: 20px; }
            .links a { color: #0066cc; text-decoration: none; margin-right: 10px; }
        </style>
    </head>
    <body>
        <h1>{$projectInfo['name']}</h1>
        <div class='info'>
            <p><strong>Описание:</strong> {$projectInfo['description']}</p>
            <p><strong>Версия:</strong> {$projectInfo['version']}</p>
        </div>
        <div class='links'>
            <a href='{$projectInfo['github']}' target='_blank'>GitHub Репозиторий</a>
            <a href='{$projectInfo['demo']}'>Перейти к приложению</a>
        </div>
    </body>
    </html>";
    exit();
} else {
    // CLI-режим для миграций и других консольных команд
    require __DIR__ . '/vendor/autoload.php';
    require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

    $config = require __DIR__ . '/config/console.php';
    $application = new yii\console\Application($config);
    $exitCode = $application->run();
    exit($exitCode);
}

