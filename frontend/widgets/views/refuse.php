<?php
/**
 * @var $this yii\web\View
 * @var $task_id int id задачи
 */
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

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
    <button class="button" type="button" data-dismiss="modal">Отмена</button>

    <?php $form = ActiveForm::begin([
        'options' => [
            'method' => 'post',
        ],
        'action' => Url::toRoute(['tasks/refuse', 'id' => $task_id]),
    ]); 
        echo Html::submitButton('Отказаться', ['class' => 'button refusal-button']);
        ActiveForm::end();
    ?>
    <button class="form-modal-close" type="button" data-dismiss="modal">Закрыть</button>
<?php Modal::end(); ?>
