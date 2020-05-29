<?php

namespace frontend\widgets;

use frontend\models\forms\RespondForm;

/**
 * отрисовка кнопки и соответствующего попапа.
 *
 * @var string $action доступное действие
 * @var int $task_id ид текущего задания для связи с контроллером
 */
class RespondWidget extends \yii\base\Widget
{
    public $action;
    public $task_id;

    public function run()
    {
        if ($this->action && $this->task_id) {
            return $this->render($this->action, ['task_id' => $this->task_id, 'model' => new RespondForm()]);
        }
    }
}
