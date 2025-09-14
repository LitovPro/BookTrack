<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
// Load .env if present, ignore parse errors (CI sets env vars)
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    try {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->safeLoad();
    } catch (\Throwable $e) {
        // Log a warning and continue; CI environment variables are used instead
        error_log('Warning: failed to load .env: ' . $e->getMessage());
    }
}
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
