<?php

namespace frontend\models\forms;

use yii\base\Model;
/**
 * This is the form class for respond's popup.
 *
 * @var $price int      запрашиваемая претендентом цена
 * @var $mark int       оценка выполнения
 * @var $comment string комментарий
 * @var $answer bool    считает ли заказчик услугу оказанной
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
            ['price', 'integer'],
            ['price', 'default', 'value' => 0],
            ['mark', 'integer', 'min' => 0, 'max' => 5],
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
