<?php
/**
 * @var $this yii\web\View
 * @var $form ActiveForm         
 * @var $model TaskSearchForm     
 * @var $dataProvider ActiveDataProvider 
 * @var $categories array список категорий
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/**
 * шаблон для отрисовки чекбоксов.
 */
$checkboxTemplateCallback = function ($index, $label, $name, $checked, $value) {
    $isChecked = $checked ? 'checked' : '';
    return  "<label class='checkbox__legend'>
    <input class='visually-hidden checkbox__input' type='checkbox' {$isChecked} name='{$name}' value='{$value}'>
    <span>{$label}</span>
    </label>";
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
            'method' => 'get',
            'options' => [
                'tag' => false,
                'class' => 'search-task__form',
            ]
        ]); ?>
        <fieldset class="search-task__categories">
            <legend>Категории</legend>
            <?= $form->field($model, 'categories')->checkboxList($categories, ['item' => $checkboxTemplateCallback])->label(false); ?>
        </fieldset>
        <fieldset class="search-task__categories">
            <legend>Дополнительно</legend>
            <?php
            echo $form->field($model, 'withoutResponds', ['labelOptions' => ['class' => "checkbox__legend"]])
            ->checkbox(['class' => "checkbox__input"]);
            echo $form->field($model, 'isDistant', ['labelOptions' => ['class' => "checkbox__legend"]])
            ->checkbox(['class' => "checkbox__input"]);
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