<?php
/**
 * @var int $task_id id задачи
 */
use yii\helpers\Url;
?>
<a class="button button__big-color refusal-button" href ="<?=Url::to(['tasks/cancel', 'id' => $task_id]); ?>">Отменить</a>
