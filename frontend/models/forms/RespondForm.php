<?php

namespace frontend\models\forms;

use yii\base\Model;
/**
 * This is the form class for respond's popup.
 *
 * @var int $price
 * @var int $mark
 * @var string $comment
 * @var bool $answer
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
            ['comment', 'string'],
            ['comment', 'trim'],
            ['price', 'integer', 'min' => 1],
            ['mark', 'integer', 'min' => 1, 'max' => 5],
            ['price', 'default', 'value' => 0],
            ['mark', 'default', 'value' => 0],
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
