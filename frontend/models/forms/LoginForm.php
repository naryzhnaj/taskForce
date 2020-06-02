<?php

namespace frontend\models\forms;

use yii\base\Model;
use frontend\models\Users;

/**
 * This is the form class for login.
 *
 * @var string $email
 * @var string $password
 * @var Users $_user
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    private $_user;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            [['email', 'password'], 'trim'],
            ['email', 'email'],
            ['email', 'exist',  'targetClass' => \frontend\models\Users::class,
                'targetAttribute' => ['email' => 'email'], 'message' => 'Такого адреса в базе нет', ],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'пароль',
        ];
    }

    /**
     * встроенный валидатор
     * проверка соответствия email и пароля.
     *
     * @param string $attribute проверяемый атрибут
     * @param array  $params    дополнительные пары имя-значение
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неправильный email или пароль');
            }
        }
    }

    /**
     * находит пользователя по email.
     *
     * @return mixed
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Users::findOne(['email' => $this->email]);
        }

        return $this->_user;
    }
}
