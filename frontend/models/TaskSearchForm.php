<?php
namespace app\models;

use yii\base\Model;

/**
 * Task search form
 */
class TaskSearchForm extends Model
{
    public $period;
    public $same_city;
    public $is_distant;
    public $category_list;
    public $title;

    public function rules()
    {
        return [
            [['same_city', 'is_distant'], 'boolean'],
            ['same_city', 'default', 'value' => false],
            ['is_distant', 'default', 'value' => false],

            ['period', 'in', 'range' => ['day', 'week', 'month']],
            ['title', 'trim'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'same_city' => 'Мой город',
            'is_distant' => 'Удаленная работа',
            'category_list' => '',
            'period' => ''
        ];
    }
}
