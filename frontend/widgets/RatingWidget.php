<?php

namespace frontend\widgets;

/**
 * отрисовка рейтинга исполнителя звездочками.
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
