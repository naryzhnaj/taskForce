<?php

namespace frontend\controllers;

use Yii;
use frontend\models\LoginForm;
use frontend\models\Tasks;
use yii\filters\AccessControl;

class LandingController extends \yii\web\Controller
{
    private const CARDS_AMOUNT = 4;

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
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'landing';
        $form = new LoginForm();

        $tasks = $query = Tasks::find()
            ->select('category_id, title, description, budget, dt_add')
            ->where(['status' => 'new'])->andWhere('end_date >= now()')
            ->orderBy(['dt_add' => SORT_DESC])
            ->limit(self::CARDS_AMOUNT)->all();

        if (Yii::$app->request->getIsPost()) {
            $form->load(Yii::$app->request->post());          
            if (Yii::$app->request->isAjax) {
                return ActiveForm::validate($form);
            }
            if ($form->validate()) {
                $user = $form->getUser();
                Yii::$app->user->login($user);

                return $this->goHome();
            }
        }

        return $this->render('index', ['tasks' => $tasks, 'model' => $form]);
    }
}