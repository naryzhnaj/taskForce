<?php
/**
 * @var $this yii\web\View
 * @var $task_id int id задачи
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
    <button class="button__form-modal button" type="button" data-dismiss="modal">Отмена</button>
    <button class="button__form-modal button refusal-button" href ="<?=Url::to(['tasks/refuse', 'id' => $task_id]); ?>">Отказаться</a>
    <button class="form-modal-close" type="button" data-dismiss="modal">Закрыть</button>
<?php Modal::end(); ?>
