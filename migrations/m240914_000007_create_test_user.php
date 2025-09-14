<?php

use yii\db\Migration;

/**
 * Creates test user
 */
class m240914_000007_create_test_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%user}}', [
            'username' => 'admin',
            'password_hash' => \Yii::$app->security->generatePasswordHash('admin123'),
            'auth_key' => \Yii::$app->security->generateRandomString(),
            'created_at' => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%user}}', ['username' => 'admin']);
    }
}
