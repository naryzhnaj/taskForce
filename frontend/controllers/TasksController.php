<?php

namespace frontend\controllers;
use Yii;
use app\models\Tasks;
use app\models\Categories;
use app\models\TaskSearchForm;

class TasksController extends \yii\web\Controller
{
    private const CARDS_AMOUNT = 5;

    public function actionIndex()
    {
        $all_categories = Categories::find()->select(['title', 'id'])->indexBy('id')->column();

        $form = new TaskSearchForm();
        // стартовый запрос
        $query = Tasks::find()->where(['status' => 'new'])->andWhere('end_date >= now()');

        // добавляются условия из формы
        if (Yii::$app->request->getIsPost()) {
            $form->load(Yii::$app->request->post());
            if ($form->validate()) {
                $form->search($query);
            }
        }
        $tasks = $query->orderBy(['dt_add'=> SORT_DESC])->limit(self::CARDS_AMOUNT)->all();

        return $this->render('index', ['tasks' => $tasks, 'all_categories' => $all_categories, 'model' => $form]);
    }
}
