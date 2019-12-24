<?php

namespace frontend\controllers;

use app\models\Users;
use app\models\Cities;
use app\models\SignupForm;
use Yii;

class SignupController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $all_cities = Cities::find()->select(['title', 'id'])->indexBy('id')->column();
        $form = new SignupForm();

        if (Yii::$app->request->getIsPost()) {
            $form->load(Yii::$app->request->post());
            if ($form->validate()) {
                $user = new Users();

                $user->password = Yii::$app->security->generatePasswordHash($form->password);
                $user->name = $form->username;
                $user->email = $form->email;
                $user->city_id = $form->city;

                $user->save();
                $this->goHome();
            }
        }

        return $this->render('index', ['model' => $form, 'cities' => $all_cities]);
    }
}
