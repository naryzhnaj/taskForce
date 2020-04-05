<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Tasks;
use frontend\models\Categories;
use frontend\models\Specialization;
use frontend\models\forms\TaskSearchForm;
use frontend\models\forms\TaskCreateForm;
use yii\web\NotFoundHttpException;

class TasksController extends \frontend\controllers\SecuredController
{
    private const CARDS_AMOUNT = 5;
    
    // закрыть исполнителям доступ к созданию заданий
    public function behaviors()
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => ['create'],
            'matchCallback' => function ($rule, $action) {
                return Specialization::isUserDoer(Yii::$app->user->id);
            },
            'denyCallback' => function ($rule, $action) {
                throw new ForbiddenHttpException('Извините, только заказчики могут создавать задачи');
            }
        ];
        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

    /**
     * выводит список последних задач
     * @throws NotFoundHttpException
     * @return mixed
     */
    public function actionIndex()
    {
        $categories = Categories::getList();
        if (!$categories) {
            throw new NotFoundHttpException('Извините, не найдено ни одной категории');
        }

        $form = new TaskSearchForm();
        // стартовый запрос
        $query = Tasks::getMainList();

        // добавляются условия из формы
        if (Yii::$app->request->getIsPost()) {
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                $form->search($query);
            }
        }

        $tasks = $query->orderBy(['dt_add' => SORT_DESC])->limit(self::CARDS_AMOUNT)->all();

        return $this->render('index', ['tasks' => $tasks, 'categories' => $categories, 'model' => $form]);
    }

    /**
     * показывает карточку конкретного задания
     * @param int $id id задания
     * @throws NotFoundHttpException
     * @return mixed
     */
    public function actionShow(int $id)
    {
        $task = Tasks::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException("Задание с ID $id не найдено");
        } elseif ($task->status !== Tasks::STATUS_NEW) {
            throw new NotFoundHttpException("Извините, задание с ID $id неактуально");
        }

        $category = Categories::findOne($task->category_id);
        $customer = $task->author;
        $responds = $task->responds;

        return $this->render('view', ['task' => $task, 'category' => $category, 'customer' => $customer, 'responds' => $responds]);
    }

    /**
     * страница с формой нового задания
     * @return mixed
     */
    public function actionCreate()
    {
        $categories = Categories::getList();
        $form = new TaskCreateForm();

        if (Yii::$app->request->getIsPost() && $form->load(Yii::$app->request->post())) {
            if ($form->validate() && $form->createTask()) {
                return $this->goHome();
            } else {
                return $this->refresh();
            }
        }
        return $this->render('create', ['categories' => $categories, 'model' => $form]);
    }
}
