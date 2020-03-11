<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "application".
 *
 * @property int $id
 * @property int $task_id
 * @property string $filename
 *
 * @property Tasks $task
 */
class Application extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'filename'], 'required'],
            [['task_id'], 'integer'],
            [['filename'], 'string', 'max' => 255],
            [['filename'], 'unique'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'filename' => 'Filename',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::className(), ['id' => 'task_id']);
    }
}
