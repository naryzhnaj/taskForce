<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Tasks;
use frontend\models\Categories;
use frontend\models\Specialization;
use frontend\models\TaskSearchForm;
use frontend\models\TaskCreateForm;
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
                throw new \Exception('Извините, только заказчики могут создавать задачи');
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
        $all_categories = Categories::find()->select(['title', 'id'])->indexBy('id')->column();
        if (!$all_categories) {
            throw new NotFoundHttpException('Извините, не найдено ни одной категории');
        }

        $form = new TaskSearchForm();
        // стартовый запрос
        $query = Tasks::find()
            ->select('tasks.id, category_id, title, description, budget, address, tasks.dt_add')
            ->where(['status' => Tasks::STATUS_NEW])->andWhere('end_date >= now() OR end_date IS NULL');

        // добавляются условия из формы
        if (Yii::$app->request->getIsPost()) {
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                $form->search($query);
            }
        }

        $tasks = $query->orderBy(['dt_add' => SORT_DESC])->limit(self::CARDS_AMOUNT)->all();

        return $this->render('index', ['tasks' => $tasks, 'all_categories' => $all_categories, 'model' => $form]);
    }

    /**
     * показывает карточку конкретного задания
     * @param integer $id id задания
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
     * создание нового задания
     * @throws NotFoundHttpException
     * @return mixed
     */
    public function actionCreate()
    {
        $all_categories = Categories::find()->select(['title', 'id'])->orderBy(['title' => SORT_ASC])->indexBy('id')->column();
        $form = new TaskCreateForm();

        if (Yii::$app->request->getIsPost() && $form->load(Yii::$app->request->post())) {
            if ($form->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $task = new Tasks();
                    $task->attributes = $form->attributes;
                    $task->author_id = Yii::$app->user->id;
                    $task->save();
                    $form->upload($task->id);
                  
                    $transaction->commit();
                    return $this->goHome();
                } catch (\Exception $e) {
                    $transaction->rollback();
                    throw new NotFoundHttpException("Извините, при сохранении произошла ошибка");
                }
            }
        }
        return $this->render('create', ['all_categories' => $all_categories, 'model' => $form]);
    }
}
