<?php
/**
 *  @var int $task_id id задачи
 */
use yii\bootstrap\Modal;
use yii\helpers\Url;

Modal::begin([
    'toggleButton' => [
        'label' => 'Отказаться',
        'tag' => 'button',
        'class' => 'button button__big-color refusal-button',
    ],
    'headerOptions' => [
        'style' => 'display:none;'
    ],
    'bodyOptions' => ['class' => 'form-modal refusal-form']
]);
?>
    <h2>Отказ от задания</h2>
    <p>
        Вы собираетесь отказаться от выполнения задания.
        Это действие приведёт к снижению вашего рейтинга.
        Вы уверены?
    </p>
    <button class="button__form-modal button" id="close-modal" type="button">Отмена</button>
    <a class="button__form-modal refusal-button button" href ="<?=Url::to(['tasks/refuse', 'id' => $task_id]); ?>">Отказаться</a>
    <button class="form-modal-close" type="button">Закрыть</button>
<?php Modal::end(); ?>
