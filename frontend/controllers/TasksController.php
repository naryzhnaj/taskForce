<?php

namespace frontend\controllers;

use app\models\Tasks;

class TasksController extends \yii\web\Controller
{
    private const CARDS_AMOUNT = 5;

    public function actionIndex()
    {
        $allTasks = Tasks::find()
            ->where(['status' => 'new'])
            ->andWhere('end_date >= now()')
            ->orderBy(['dt_add'=> SORT_DESC])
            ->limit(self::CARDS_AMOUNT)->all();

        return $this->render('index', ['allTasks' => $allTasks]);
    }
}
