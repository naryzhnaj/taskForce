<?php

namespace frontend\controllers;
use Yii;
use app\models\Tasks;
use app\models\Categories;
use app\models\TaskSearchForm;
use yii\web\NotFoundHttpException;

class TasksController extends \yii\web\Controller
{
    private const CARDS_AMOUNT = 5;

    public function actionIndex()
    {
        $all_categories = Categories::find()->select(['title', 'id'])->indexBy('id')->column();
        if (!$all_categories) {
            throw new NotFoundHttpException('Извините, не найдено ни одной категории');
        }

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

    public function actionShow($id)
    {
        $task = Tasks::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException("Задание с ID $id не найдено");
        } elseif ($task->status !== 'new') {
            throw new NotFoundHttpException("Извините, задание с ID $id неактуально");
        }
        $category = Categories::findOne($task->category_id);
        $customer = $task->author;
        $responds = $task->responds;

        return $this->render('view', ['task' => $task, 'category' => $category, 'customer' => $customer, 'responds' => $responds]);
    }
}
