<?php

namespace frontend\models;
use frontend\models\Users;

/**
 * This is the model class for table "tasks".
 *
 * @property int          $id
 * @property int          $author_id
 * @property int          $city_id
 * @property int          $executor_id
 * @property int          $category_id
 * @property string       $title
 * @property string       $description
 * @property string       $end_date
 * @property string       $budget
 * @property string       $address
 * @property string       $lat
 * @property string       $longitude
 * @property string       $status
 * @property string       $dt_add
 * @property Attachment[] $attachments
 * @property Chats[]      $chats
 * @property Responds[]   $responds
 * @property Reviews[]    $reviews
 * @property Users        $author
 * @property Users        $executor
 * @property Cities       $city
 * @property Categories   $category
 */
class Tasks extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_PROGRESS = 'in_progress';

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
            [['description'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 11],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['author_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['executor_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
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
            'status' => 'Status',
            'dt_add' => 'Dt Add',
        ];
    }

    /**
     * получает список приложений.
     *
     * @return array
     */
    public function getAttachments()
    {
        return $this->hasMany(Attachment::class, ['task_id' => 'id'])->select('filename')->orderBy(['filename' => SORT_ASC])->column();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChats()
    {
        return $this->hasMany(Chats::class, ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResponds()
    {
        return $this->hasMany(Responds::class, ['task_id' => 'id']);
    }

    /**
     * проверка, что кандидат вправе оставить отклик.
     * 
     * @param int $id id гостя
     *
     * @return bool
     */
    public function checkCandidate(int $id): bool
    {
        return (Users::findOne($id)->isDoer() &&
            !$this->hasMany(Responds::class, ['task_id' => 'id'])->where(['responds.author_id' => $id])->exists());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Reviews::class, ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Users::class, ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(Users::class, ['id' => 'executor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * выборка свободных заданий для лэндинга.
     *
     * @return array
     */
    public static function getRecent(int $amount)
    {
        return self::find()
            ->where(['status' => self::STATUS_NEW])->andWhere('end_date >= now() OR end_date IS NULL')
            ->orderBy(['dt_add' => SORT_DESC])->limit($amount)->all();
    }

    /**
     * выборка свободных заданий для главной страницы.
     *
     * @return \yii\db\ActiveQuery
     */
    public static function getMainList()
    {
        return self::find()->where(['status' => self::STATUS_NEW])
            ->andWhere('end_date >= now() OR end_date IS NULL')
            ->indexBy('id');
    }

    /**
     * является ли пользователь заказчиком
     *
     * @return boolean
     */
    private function isUserCustomer()
    {
        return $this->author_id === \Yii::$app->user->id;
    }

    /**
     * получить список видимых пользователю откликов.
     *
     * @return array
     */
    public function getVisibleResponds()
    {
        return ($this->isUserCustomer() && $this->status === self::STATUS_NEW) ?
            $this->getResponds()->where(['responds.status' => self::STATUS_NEW])->all() : [];
    }

    /**
     * отображаемое контактное лицо в блоке сообщений на стр.просмотра.
     *
     * @return Users
     */
    public function getContact()
    {
        return ($this->isUserCustomer() && $this->status === self::STATUS_PROGRESS) ? $this->executor : $this->author;
    }
}
