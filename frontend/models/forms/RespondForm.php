<?php

namespace frontend\models\forms;

use yii\base\Model;

class RespondForm extends Model
{
    public $price;
    public $comment;
    public $value;
    public $answer;

    public function rules()
    {
        return [
            ['answer', 'boolean'],
            ['comment', 'string'],
            ['comment', 'trim'],
            ['price', 'integer', 'min' => 1],
            ['value', 'integer', 'min' => 1, 'max' => 5],
        ];
    }

    public function attributeLabels()
    {
        return [
            'price' => 'Ваша цена',
            'value' => '',
            'comment' => 'комментарий',
        ];
    }
}
