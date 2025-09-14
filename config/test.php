<?php

$config = require __DIR__ . '/web.php';

// Test database configuration
$config['components']['db'] = [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=booktrack_test',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
];

// Disable debug and gii modules in tests
unset($config['modules']['debug']);
unset($config['modules']['gii']);

return $config;

