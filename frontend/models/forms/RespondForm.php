<?php

namespace frontend\models\forms;

use yii\base\Model;
/**
 * This is the form class for respond's popup.
 *
 * @var $price int
 * @var $mark int
 * @var $comment string
 * @var $answer bool
 */
class RespondForm extends Model
{
    public $price;
    public $comment;
    public $mark;
    public $answer;

    public function rules()
    {
        return [
            ['answer', 'boolean'],
            ['answer', 'default', 'value' => true],
            ['comment', 'string'],
            ['comment', 'trim'],
            ['price', 'integer', 'min' => 1],
            ['mark', 'integer', 'min' => 0, 'max' => 5],
            ['price', 'required', 'message' => 'Укажите размер вознаграждения'],
            ['mark', 'default', 'value' => 0]
        ];
    }

    public function attributeLabels()
    {
        return [
            'price' => 'Ваша цена',
            'mark' => '',
            'comment' => 'комментарий',
        ];
    }
}
