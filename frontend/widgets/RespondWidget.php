<?php

namespace frontend\widgets;

use frontend\models\forms\RespondForm;

/**
 * отрисовка кнопки и соответствующего попапа.
 */
class RespondWidget extends \yii\base\Widget
{
    public $actions;
    public $task_id;

    public function run()
    {
        if ($this->actions) {
            echo $this->render($this->action, ['task_id' => $this->task_id, 'model' => new RespondForm()]);
        }
    }
}
