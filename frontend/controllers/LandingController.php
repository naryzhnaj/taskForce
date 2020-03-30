<?php

namespace frontend\controllers;

use Yii;
use frontend\models\LoginForm;
use frontend\models\Tasks;
use yii\filters\AccessControl;

class LandingController extends \yii\web\Controller
{
    // кол-во выводимых для примера заданий
    private const CARDS_AMOUNT = 4;

    // страница доступна толко для гостей
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

    /**
     * отрисовывает лэндинг и форму входа
     * при успешной авторизации юзер переадресуется на страницу с заданиями.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'landing';
        $form = new LoginForm();

        // последние задания для примера
        $tasks = $query = Tasks::find()
            ->select('category_id, title, description, budget, dt_add')
            ->where(['status' => Tasks::STATUS_NEW])->andWhere('end_date >= now()')
            ->orderBy(['dt_add' => SORT_DESC])
            ->limit(self::CARDS_AMOUNT)->all();

        if (Yii::$app->request->getIsPost() && $form->load(Yii::$app->request->post())) {
            if ($form->validate()) {
                $user = $form->getUser();
                Yii::$app->user->login($user);

                return $this->goHome();
            }
        }

        return $this->render('index', ['tasks' => $tasks, 'model' => $form]);
    }
}
