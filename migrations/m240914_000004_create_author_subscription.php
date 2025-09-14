<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%author_subscription}}`.
 */
class m240914_000004_create_author_subscription extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%author_subscription}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'phone' => $this->string(32)->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_sub_author', '{{%author_subscription}}', 'author_id', '{{%author}}', 'id', 'CASCADE');
        $this->createIndex('idx_sub_author', '{{%author_subscription}}', 'author_id');
        $this->createIndex('uidx_sub_author_phone', '{{%author_subscription}}', ['author_id', 'phone'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%author_subscription}}');
    }
}

