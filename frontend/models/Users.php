<?php

namespace frontend\models;

/**
 * This is the model class for table "users".
 *
 * @property int              $id
 * @property int              $city_id
 * @property string           $name
 * @property string           $email
 * @property string           $password
 * will be deleted @property string           $rating
 * will be deleted @property int              $orders
 * will be deleted @property int              $failures
 * will be deleted @property int              $popularity
 * @property string           $dt_add
 * @property Accounts         $account
 * @property Chats[]          $chats
 * @property Chats[]          $chats0
 * @property Favorites[]      $favorites
 * @property Favorites[]      $favorites0
 * @property Responds[]       $responds
 * @property Reviews[]        $reviews
 * @property Specialization[] $specializations
 * @property Tasks[]          $tasks
 * @property Tasks[]          $tasks0
 * @property Cities           $city
 */
class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
    }

    public function validateAuthKey($authKey)
    {
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city_id', 'failures'], 'integer'],
            [['name', 'email', 'password'], 'required'],
            [['dt_add'], 'safe'],
            [['name', 'email'], 'string', 'max' => 60],
            [['password'], 'string', 'max' => 128],
            ['email', 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => 'City ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'failures' => 'число провалов',
            'dt_add' => 'дата регистрации',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Accounts::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChats()
    {
        return $this->hasMany(Chats::class, ['receiver_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChats0()
    {
        return $this->hasMany(Chats::class, ['sender_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(Favorites::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResponds()
    {
        return $this->hasMany(Responds::class, ['author_id' => 'id']);
    }

    /**
     * подсчет кол-ва отзывов.
     *
     * @return int
     */ 
    public function getReviewsAmount()
    {
        return $this->hasMany(Reviews::class, ['user_id' => 'id'])->count() ?? 0;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Reviews::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpecialization()
    {
        return $this->hasMany(Specialization::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Tasks::class, ['author_id' => 'id']);
    }

    /**
     * подсчет общего числа заданий в роли исполнителя
     *
     * @return integer
     */
    public function getOrdersAmount()
    {
        return $this->hasMany(Tasks::class, ['executor_id' => 'id'])->count() ?? 0;
    }

    /**
     * подсчет общего числа проваленных заданий в роли исполнителя
     * 
     * @return integer
     */
    public function getFailuresAmount()
    {
        return $this->hasMany(Tasks::class, ['executor_id' => 'id', 'status' => 'fail'])->count() ?? 0;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }

    /**
     * проверка, является ли пользователь исполнителем
     *
     * @return boolean
     */
    public function isDoer(): bool
    {
        return $this->getSpecialization()->exists();
    }
 
    /**
     * получить список навыков-категорий.
     *
     * @return array
     */
    public function getCategories()
    {
        return $this->hasMany(Categories::class, ['id' => 'category_id'])
            ->viaTable('specialization', ['user_id' => 'id'])->all();
    }

    /**
     * пересчет рейтинга
     *
     * @return number
     */
    public function getRating()
    {
        return $this->hasMany(Reviews::class, ['user_id' => 'id'])->average('value') ?? 0;
    }
}
