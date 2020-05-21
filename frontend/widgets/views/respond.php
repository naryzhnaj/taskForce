<?php
/**
 * @var yii\web\View
 * @var $model       RespondForm
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
    'bodyOptions' => ['class' => 'form-modal response-form'],
    'closeButton' => ['class' => 'form-modal-close'],
]);
?>
    <h2>Отклик на задание</h2>
    <?php $form = ActiveForm::begin([
        'id' => 'respond-form',
        'enableClientValidation' => true,
        'validateOnSubmit' => true,
        'options' => [
            'method' => 'post',
        ],
        'action' => Url::toRoute(['tasks/respond', 'id' => $task_id]),
        'fieldConfig' => [
            'options' => [
                'tag' => false,
                'template' => "{label}<br>{input}",
        ],
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
<?php Modal::end(); ?>
