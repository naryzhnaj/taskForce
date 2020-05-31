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

/**
 * шаблон для отрисовки radio.
 */
$radioTemplateCallback = function ($index, $label, $name, $checked, $value) {
    $result = ($index === 0) ? 'yes' : 'difficult';

    return Html::radio($name, $checked, ['value' => 1 - $index, 'id' => $index,
        'class' => "visually-hidden completion-input completion-input--$result", ])
        .Html::label($label, $index, ['class' => "completion-label completion-label--$result"]);
};

Modal::begin([
    'toggleButton' => [
        'label' => 'Завершить',
        'tag' => 'button',
        'class' => 'button button__big-color request-button',
    ],
    'headerOptions' => [
        'style' => 'display:none;'
    ],
    'bodyOptions' => ['class' => 'form-modal completion-form'],
]);
?>
    <h2>Завершение задания</h2>
    <p class="form-modal-description">Задание выполнено?</p>
    <?php $form = ActiveForm::begin([
        'id' => 'completion-form',
        'enableClientValidation' => true,
        'validateOnSubmit' => true,
        'options' => [
            'method' => 'post',
        ],
        'action' => Url::to(['tasks/complete', 'id' => $task_id]),
        'fieldConfig' => [
            'options' => [
                'tag' => false,
            ],
            'labelOptions' => ['class' => 'form-modal-description'],
        ],
    ]);
        echo $form->field($model, 'answer')->radioList(['Да', 'Возникли проблемы'], ['item' => $radioTemplateCallback])->label(false);
    ?>
    <p>
        <?= $form->field($model, 'comment')->textarea(['class' => 'input textarea', 'rows' => 4, 'placeholder' => 'Place your text'])
            ->label('Комментарий', ['class' => 'form-modal-description']); ?>
    </p>
    <p class="form-modal-description">
        Оценка
        <div class="feedback-card__top--name completion-form-star">
            <span class="star-disabled"></span>
            <span class="star-disabled"></span>
            <span class="star-disabled"></span>
            <span class="star-disabled"></span>
            <span class="star-disabled"></span>
        </div>
    </p>
    <?php
        echo $form->field($model, 'mark')->hiddenInput(['id' => 'rating']);
        echo Html::submitButton('Отправить', ['class' => 'button modal-button']);
        ActiveForm::end();
    ?>
    <button class="form-modal-close" type="button">Закрыть</button>
<?php Modal::end(); ?>
