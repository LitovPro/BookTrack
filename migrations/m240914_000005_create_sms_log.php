<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sms_log}}`.
 */
class m240914_000005_create_sms_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sms_log}}', [
            'id' => $this->primaryKey(),
            'phone' => $this->string(32)->notNull(),
            'message' => $this->text()->notNull(),
            'status' => $this->string(32)->notNull(),
            'response_json' => $this->text(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_sms_phone', '{{%sms_log}}', 'phone');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sms_log}}');
    }
}

