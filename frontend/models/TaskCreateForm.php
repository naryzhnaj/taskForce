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

            ['files', 'file', 'maxFiles' => 0],
            ['category_id', 'required', 'message' => 'Задание должно принадлежать одной из категорий'],
            ['category_id', 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category_id' => 'id']],

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
     * если есть приложения, то сохраняет на сервере в папке uploads и в БД.
     *
     * @param int $task id записи
     *
     * @return bool
     */
    public function upload(int $task): bool
    {
        $this->files = UploadedFile::getInstances($this, 'files');
        if (isset($this->files)) {
            foreach ($this->files as $file) {
                $filename = $task.'_'.$file->baseName.'.'.$file->extension;
                if (!$file->saveAs("uploads/$filename")) {
                    throw new \Exception("Не удалось сохранить $file->baseName");
                }
                $new_file = new Attachment();
                $new_file->task_id = $task;
                $new_file->filename = $filename;
                if (!$new_file->save()) {
                    throw new \Exception("Не удалось сохранить $file->name в базу");
                }
            }
        }

        return true;
    }
}
