<?php

use yii\db\Migration;

/**
 * Handles the creation of table `application`.
 */
class m200308_200737_create_application_table extends Migration
{
    /**
     * создаем новую таблицу для приложений и внешний ключ на соответ. задание
     */
    public function safeUp()
    {
        $this->createTable('application', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'filename' => $this->string()->notNull()->unique(),
        ]);
        $this->addForeignKey('fk-task_id', 'application', 'task_id', 'tasks', 'id', 'CASCADE');

        $this->dropColumn('tasks', 'image');
    }

    /**
     * удаляем внешний ключ и таблицу
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-task_id', 'application');
        $this->dropTable('application');
    }
}
