<?php

namespace frontend\controllers;

use app\models\Tasks;

class TasksController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $allTasks = Tasks::find()->where(['status' => 'new'])->orderBy(['dt_add'=> SORT_DESC])->limit(10)->all();
        return $this->render('index', ['allTasks' => $allTasks]);
    }
}
