<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => getenv('DB_DSN') ?: sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        getenv('DB_HOST') ?: '127.0.0.1',
        getenv('DB_PORT') ?: '3306',
        getenv('DB_NAME') ?: 'booktrack',
        getenv('DB_CHARSET') ?: 'utf8mb4'
    ),
    'username' => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',

    // Disable schema cache in test/dev environments
    'enableSchemaCache' => YII_ENV === 'prod',
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];

