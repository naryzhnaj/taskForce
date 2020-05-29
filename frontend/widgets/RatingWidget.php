<?php

namespace frontend\widgets;

/**
 * отрисовка рейтинга исполнителя звездочками.
 *
 * @var int MAX_RATE максимальный рейтинг
 * @var float $rating значение для отрисовки
 */
class RatingWidget extends \yii\base\Widget
{
    const MAX_RATE = 5;
    public $rating;

    public function run()
    {
        $n = round($this->rating);

        return str_repeat('<span></span>', $n).str_repeat('<span class="star-disabled"></span>', self::MAX_RATE - $n);
    }
}
