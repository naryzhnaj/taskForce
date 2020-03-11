<?php

namespace frontend\models;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Task Create form.
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

            ['files', 'file', 'maxFiles' => 10],
            ['category_id', 'required', 'message' => 'Задание должно принадлежать одной из категорий'],
            ['category_id', 'in', 'range' => \frontend\models\Categories::find()->select('id')->asArray()->column()],
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
     * если есть приложения, то сохраняет на сервере в папке uploads и в DB.
     *
     * @param int $task id записи
     *
     * @return bool
     */
    public function upload(int $task): bool
    {
        $this->files = UploadedFile::getInstances($this, 'files');
        if ($this->files) {
            foreach ($this->files as $file) {
                $filename = $task.'_'.$file->baseName.'.'.$file->extension;
                $file->saveAs("uploads/$filename");

                $new_file = new Application();
                $new_file->task_id = $task;
                $new_file->filename = $filename;
                $new_file->save();
            }
        }

        return true;
    }
}
