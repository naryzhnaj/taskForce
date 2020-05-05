<?php
/**
 * @var yii\web\View
 * @var ActiveForm     $form
 * @var TaskSearchForm $model
 * @var ActiveDataProvider $dataProvider
 * @var array          $categories список категорий
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/**
 * шаблон для отрисовки чекбоксов.
 */
$checkboxTemplateCallback = function ($index, $label, $name, $checked, $value) {
    return '<div class="form-group">'
        .Html::checkbox($name, $checked, ['value' => $value, 'id' => $index, 'class' => 'visually-hidden checkbox__input'])
        .Html::label($label, $index).'</div>';
};

$this->title = 'Новые задания';
?>
<section class="new-task">
    <div class="new-task__wrapper">
        <h1>Новые задания</h1>
        <?php
            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_item',
            ]);
        ?>
    </div>
</section>
<section  class="search-task">
    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin([
            'id' => 'search-task',
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'validateOnChange' => true,
            'options' => [
            'method' => 'post',
            'class' => 'search-task__form',
            ],
            'fieldConfig' => [
                'options' => ['tag' => false],
            ],
        ]); ?>
        <fieldset class="search-task__categories">
            <legend>Категории</legend>
            <?= $form->field($model, 'categories')->checkboxList($categories, ['item' => $checkboxTemplateCallback])->label(false); ?>
        </fieldset>
        <fieldset class="search-task__categories">
            <legend>Дополнительно</legend>
            <?php
                echo $form->field($model, 'without_responds', ['template' => '{input}{label}{error}'])->
                    checkbox(['class' => 'visually-hidden checkbox__input'], false);
                echo $form->field($model, 'is_distant', ['template' => '{input}{label}{error}'])->
                    checkbox(['class' => 'visually-hidden checkbox__input'], false);
            ?>
        </fieldset>
        <?php
            echo $form->field($model, 'period', ['template' => '{label}<br>{input}'])->dropDownList([
                'all' => 'За всё время',
                'day' => 'За день',
                'week' => 'За неделю',
                'month' => 'За месяц', ],
                ['class' => 'multiple-select input'])
                ->label('Период', ['class' => 'search-task__name']);
            echo $form->field($model, 'title')->input('search', ['class' => 'input-middle input'])
                ->label('Поиск по названию', ['class' => 'search-task__name']);

            echo Html::submitButton('Искать', ['class' => 'button']);
            ActiveForm::end();
        ?>
    </div>
</section>
