<?php
/**
 * @var yii\web\View $this
 * @var RespondForm $model
 * @var int $task_id id задачи
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Url;

Modal::begin([
    'toggleButton' => [
        'label' => 'Откликнуться',
        'tag' => 'button',
        'class' => 'button button__big-color response-button',
    ],
    'headerOptions' => [
        'style' => 'display:none;'
    ],
    'bodyOptions' => ['class' => 'form-modal response-form']
]);
?>
    <h2>Отклик на задание</h2>
    <?php $form = ActiveForm::begin([
        'id' => 'respond-form',
        'enableClientValidation' => true,
        'validateOnChange' => true,
        'validateOnSubmit' => true,
        'options' => [
            'method' => 'post',
        ],
        'action' => Url::toRoute(['tasks/respond', 'id' => $task_id]),
        'fieldConfig' => [
            'template' => "{label}<br>{input}<br>{error}",
            'labelOptions' => ['class' => 'form-modal-description'],
        ],
    ]); ?>
    <p>
        <?= $form->field($model, 'price')->textInput(['class' => 'response-form-payment input input-middle input-money']); ?>
    </p>
    <p>
        <?= $form->field($model, 'comment')->textarea(['class' => 'input textarea', 'rows' => 4, 'placeholder' => 'Place your text']); ?>
    </p>
    <?php
        echo Html::submitButton('Отправить', ['class' => 'button modal-button']);
        ActiveForm::end();
    ?>
    <button class="form-modal-close" type="button">Закрыть</button>
<?php Modal::end(); ?>
