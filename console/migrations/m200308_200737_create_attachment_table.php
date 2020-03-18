<?php

use yii\db\Migration;

/**
 * Handles the creation of table `attachment`.
 */
class m200308_200737_create_attachment_table extends Migration
{
    /**
     * создаем новую таблицу для приложений и внешний ключ на соответ. задание
     */
    public function safeUp()
    {
        $this->createTable('attachment', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'filename' => $this->string()->notNull()->unique(),
        ]);
        $this->addForeignKey('fk-task_id', 'attachment', 'task_id', 'tasks', 'id', 'CASCADE');

        $this->dropColumn('tasks', 'image');
    }

    /**
     * удаляем внешний ключ и таблицу
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-task_id', 'attachment');
        $this->dropTable('attachment');
        $this->addColumn('tasks', 'image', $this->string());
    }
}
