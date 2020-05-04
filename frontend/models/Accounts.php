<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "accounts".
 *
 * @property int $id
 * @property int $user_id
 * @property string $address
 * @property string $phone
 * @property string $skype
 * @property string $contact
 * @property string $avatar
 * @property string $bio
 * @property string $birth_date
 * @property string $portfolio
 * @property int $is_free
 * @property string $last_activity
 * @property int $notifications_allowed
 * @property int $is_visible
 * @property int $contacts_visible
 *
 * @property Users $user
 */
class Accounts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'accounts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'is_free', 'notifications_allowed', 'is_visible', 'contacts_visible'], 'integer'],
            [['birth_date'], 'required'],
            [['birth_date', 'last_activity'], 'safe'],
            [['address', 'contact'], 'string', 'max' => 60],
            [['phone'], 'string', 'max' => 11],
            [['skype'], 'string', 'max' => 20],
            [['avatar', 'bio', 'portfolio'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'address' => 'Address',
            'phone' => 'Phone',
            'skype' => 'Skype',
            'contact' => 'Contact',
            'avatar' => 'Avatar',
            'bio' => 'Bio',
            'birth_date' => 'Birth Date',
            'portfolio' => 'Portfolio',
            'is_free' => 'Is Free',
            'last_activity' => 'Last Activity',
            'notifications_allowed' => 'Notifications Allowed',
            'is_visible' => 'Is Visible',
            'contacts_visible' => 'Contacts Visible',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
