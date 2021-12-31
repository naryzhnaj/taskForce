<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Categories;
use frontend\models\Users;
use frontend\models\forms\UserSearchForm;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class UsersController extends \frontend\controllers\SecuredController
{
    private const CARDS_AMOUNT = 5;

    /**
     * выводит список новых исполнителей.
     *
     * @throws NotFoundHttpException
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $form = new UserSearchForm();
        $form->load(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => $form->search(),
                'pagination' => [
                    'pageSize' => self::CARDS_AMOUNT,
                ],
            ]),
            'categories' => Categories::getList(),
            'model' => $form
        ]);
    }

    /**
     * показывает карточку конкретного исполнителя.
     *
     * @param int $id id исполнителя
     *
     * @throws NotFoundHttpException
     *
     * @return mixed
     */
    public function actionView(int $id)
    {
        $user = Users::findOne($id);
        if (!$user || !$user->isDoer()) {
            throw new NotFoundHttpException('исполнитель не найден');
        }

        return $this->render('view', ['user' => $user]);
    }
}
