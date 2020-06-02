<?php

namespace frontend\models\forms;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use frontend\models\Tasks;
use frontend\models\Attachment;

/**
 * This is the form class for creating task.
 *
 * @var string $title
 * @var string $description
 * @var int $budget
 * @var int $category_id
 * @var string $location
 * @var date $end_date
 * @var file $files
 */
class TaskCreateForm extends Model
{
    public $title;
    public $description;
    public $budget;
    public $location;
    public $category_id;
    public $end_date;
    public $files;

    public function rules()
    {
        return [
            [['title', 'description'], 'required', 'message' => 'Это поле необходимо заполнить'],
            [['title', 'description'], 'trim'],
            ['title', 'string', 'min' => 10],
            ['description', 'string', 'min' => 30],

            ['files', 'file', 'maxFiles' => 0],
            ['category_id', 'required', 'message' => 'Задание должно принадлежать одной из категорий'],
            ['category_id', 'exist', 'skipOnError' => false, 'targetClass' => \frontend\models\Categories::class, 'targetAttribute' => ['category_id' => 'id']],

            ['budget', 'integer', 'min' => 1],
            ['end_date', 'date', 'min' => time(), 'message' => 'Выберите дату из будущего'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Мне нужно',
            'description' => 'Подробности задания',
            'category_id' => 'Категория',
            'end_date' => 'Срок исполнения',
            'budget' => 'Бюджет',
            'location' => 'Локация',
            'files' => '',
        ];
    }

    /**
     * если есть приложения, то сохраняет на сервере и в БД.
     *
     * @param int $task id записи
     * @throws Exception
     */
    private function upload(int $task): void
    {
        $this->files = UploadedFile::getInstances($this, 'files');

        if (!empty($this->files)) {
            foreach ($this->files as $file) {
                $filename = sprintf('%s_%s.%s', $task, $file->baseName, $file->extension);

                $new_file = new Attachment();
                $new_file->task_id = $task;
                $new_file->filename = $filename;

                if (!$new_file->save() || !$file->saveAs(sprintf('%s/%s', Yii::getAlias('@uploads'), $filename))) {
                    throw new \Exception("Не удалось сохранить $filename в базу");
                }
            }
        }
    }
    
    /**
     * сохранение новой задачи
     *
     * @throws ServerErrorHttpException
     */
    public function createTask(): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $task = new Tasks();
            $task->attributes = $this->attributes;
            $task->author_id = Yii::$app->user->id;
            $task->save();
            $this->upload($task->id);
      
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \yii\web\ServerErrorHttpException("Извините, при сохранении произошла ошибка");
        }
    }
}
