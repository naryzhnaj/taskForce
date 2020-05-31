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
     * @param string $sort критерий сортировки
     * @throws NotFoundHttpException
     *
     * @return mixed
     */
    public function actionIndex($sort = 'rating')
    {
        $form = new UserSearchForm();
        $sortTypes = ['rating', 'orders', 'popularity'];

        $query = Users::getDoersList();

        if (!$query || !in_array($sort, $sortTypes)) {
            throw new NotFoundHttpException('по вашему запросу ничего не найдено');
        }

        // добавляются условия из формы
        if (Yii::$app->request->getIsPost()) {
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                $query = $form->search($query);
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [$sort => SORT_DESC]],
            'pagination' => [
                'pageSize' => self::CARDS_AMOUNT,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
        if (!$user || !Users::isUserDoer($id)) {
            throw new NotFoundHttpException('исполнитель не найден');
        }

        return $this->render('view', ['user' => $user]);
    }
}
