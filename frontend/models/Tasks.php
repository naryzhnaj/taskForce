<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property int $author_id
 * @property int $city_id
 * @property int $executor_id
 * @property int $category_id
 * @property string $title
 * @property string $description
 * @property string $end_date
 * @property string $budget
 * @property string $address
 * @property string $lat
 * @property string $longitude
 * @property string $image
 * @property string $status
 * @property string $dt_add
 *
 * @property Chats[] $chats
 * @property Responds[] $responds
 * @property Reviews[] $reviews
 * @property Users $author
 * @property Users $executor
 * @property Cities $city
 * @property Categories $category
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id', 'city_id', 'executor_id', 'category_id', 'budget'], 'integer'],
            [['title', 'description'], 'required'],
            [['end_date', 'dt_add'], 'safe'],
            [['lat', 'longitude'], 'number'],
            [['title', 'address'], 'string', 'max' => 128],
            [['description', 'image'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 11],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['executor_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category_id' => 'id']],
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
            'city_id' => 'City ID',
            'executor_id' => 'Executor ID',
            'category_id' => 'Category ID',
            'title' => 'Title',
            'description' => 'Description',
            'end_date' => 'End Date',
            'budget' => 'Budget',
            'address' => 'Address',
            'lat' => 'Lat',
            'longitude' => 'Longitude',
            'image' => 'Image',
            'status' => 'Status',
            'dt_add' => 'Dt Add',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChats()
    {
        return $this->hasMany(Chats::className(), ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResponds()
    {
        return $this->hasMany(Responds::className(), ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Reviews::className(), ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Users::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(Users::className(), ['id' => 'executor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'category_id']);
    }
}
