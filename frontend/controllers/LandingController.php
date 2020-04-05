<?php

namespace frontend\controllers;

use Yii;
use frontend\models\forms\LoginForm;
use frontend\models\Tasks;
use yii\filters\AccessControl;
use yii\widgets\ActiveForm;
use yii\web\Response;

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
     * при успешной авторизации гость переадресуется на страницу с заданиями.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'landing';
        $form = new LoginForm();
        $this->view->params['model'] = $form;

        // последние задания для демонстрации
        $tasks = Tasks::getRecent(self::CARDS_AMOUNT);

        if (Yii::$app->request->getIsPost() && Yii::$app->request->isAjax) {
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                $user = $form->getUser();
                Yii::$app->user->login($user);

                return $this->goHome();
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($form);
            }
        }

        return $this->render('index', ['tasks' => $tasks]);
    }
}
