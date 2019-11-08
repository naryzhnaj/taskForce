<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "responds".
 *
 * @property int $id
 * @property int $author_id
 * @property int $task_id
 * @property string $dt_add
 * @property int $price
 * @property string $comment
 * @property string $status
 *
 * @property Tasks $task
 * @property Users $author
 */
class Responds extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'responds';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id', 'task_id', 'price'], 'integer'],
            [['dt_add'], 'safe'],
            [['price'], 'required'],
            [['comment'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 11],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Author ID',
            'task_id' => 'Task ID',
            'dt_add' => 'Dt Add',
            'price' => 'Price',
            'comment' => 'Comment',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::className(), ['id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Users::className(), ['id' => 'author_id']);
    }
}
