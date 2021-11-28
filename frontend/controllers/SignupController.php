<?php

namespace frontend\controllers;

use yii\filters\AccessControl;
use frontend\models\forms\SignupForm;
use frontend\models\Cities;
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
                        'roles' => ['?'],
                        'actions' => ['index']
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['logout']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $form = new SignupForm();

        if (Yii::$app->request->getIsPost() && $form->load(Yii::$app->request->post()) && $form->validate()) {
            $form->createUser();    
            $this->goHome();
        }
        return $this->render('index', [
            'model' => $form,
            'cities' => Cities::getList()]);
    }
    
    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
