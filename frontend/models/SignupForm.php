<?php
namespace app\models;

use yii\base\Model;

/**
 * Signup form
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
            [['username', 'email'], 'trim'],
            ['city', 'safe'],
            [['email', 'password'], 'required', 'message' => 'Это поле необходимо заполнить'],
            ['email', 'email', 'message' => 'Введите валидный адрес электронной почты'],
            ['email', 'unique', 'targetClass' => '\app\models\Users', 'message' => 'Извините, данный адрес занят'],
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
