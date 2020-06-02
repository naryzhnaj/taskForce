<?php

namespace frontend\models\forms;

use yii\base\Model;

/**
 * This is the form class for signup.
 *
 * @var string $email
 * @var string $password
 * @var string $username
 * @var int $city
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $city;

    public function rules()
    {
        return [
            ['username', 'required', 'message' => 'Введите ваше имя и фамилию'],
            [['username', 'password', 'email'], 'trim'],
            ['city', 'exist', 'targetClass' => \frontend\models\Cities::class, 'targetAttribute' => ['city' => 'id']],
            [['email', 'password'], 'required', 'message' => 'Это поле необходимо заполнить'],
            ['email', 'email', 'message' => 'Введите валидный адрес электронной почты'],
            ['email', 'unique', 'targetClass' => \frontend\models\Users::class, 'message' => 'Извините, данный адрес занят'],
            ['password', 'string', 'min' => 8, 'message' => 'Длина пароля от 8 символов'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'city' => 'город проживания',
            'username' => 'ваше имя',
            'email' => 'электронная почта',
            'password' => 'пароль',
        ];
    }
}
