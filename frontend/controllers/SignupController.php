<?php

namespace frontend\controllers;

use yii\filters\AccessControl;
use frontend\models\Users;
use frontend\models\Cities;
use frontend\models\SignupForm;
use Yii;

class SignupController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function ($rule, $action) {
                    $this->goHome();
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $all_cities = Cities::find()->select(['title', 'id'])->indexBy('id')->column();
        $form = new SignupForm();

        if (Yii::$app->request->getIsPost()) {
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                $user = new Users();

                $user->password = Yii::$app->security->generatePasswordHash($form->password);
                $user->name = $form->username;
                $user->email = $form->email;
                $user->city_id = $form->city;

                $user->save(false);             
                $this->goHome();
            }
        }

        return $this->render('index', ['model' => $form, 'cities' => $all_cities]);
    }
}
