<?php

namespace frontend\controllers;

use app\models\Tasks;
use app\models\Categories;
use app\models\TaskSearchForm;

class TasksController extends \yii\web\Controller
{
    private const CARDS_AMOUNT = 5;

    public function actionIndex()
    {
        $model = new TaskSearchForm();
        $all_categories = Categories::find()->select(['title', 'id'])->column();
        $query = Tasks::find()->where(['status' => 'new'])->andWhere('end_date >= now()');
        $allTasks = $query->orderBy(['dt_add'=> SORT_DESC])->limit(self::CARDS_AMOUNT)->all();

        return $this->render('index', ['allTasks' => $allTasks, 'all_categories' => $all_categories, 'model'=>$model]);
    }
}
