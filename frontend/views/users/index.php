<?php
/**
 * @var $this yii\web\View
 * @var $form ActiveForm
 * @var $model UserSearchForm
 * @var $dataProvider ActiveDataProvider
 * @var $categories array список категорий
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\helpers\Url;

$this->title = 'Исполнители';

/**
 * шаблон для отрисовки чекбоксов.
 */
$checkboxTemplateCallback = function ($index, $label, $name, $checked, $value): string {
    $isChecked = $checked ? 'checked' : '';
    return   "<label class='checkbox__legend'>
    <input class='visually-hidden checkbox__input' type='checkbox' {$isChecked} name='{$name}' value='{$value}'>
    <span>{$label}</span>
    </label>";
}
?>
<section class="user__search">
    <?php
        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_item',
        ]);
    ?>
</section>
<section  class="search-task">
    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin([
            'id' => 'search-user',
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
            echo $form->field($model, 'isFree', ['labelOptions' => ['class' => "checkbox__legend"]])
                ->checkbox(['class' => 'checkbox__input']);
            echo $form->field($model, 'isOnline', ['labelOptions' => ['class' => "checkbox__legend"]])
                ->checkbox(['class' => 'checkbox__input']);
            echo $form->field($model, 'isFavorite', ['labelOptions' => ['class' => "checkbox__legend"]])
                ->checkbox(['class' => 'checkbox__input']);
            echo $form->field($model, 'withReviews', ['labelOptions' => ['class' => "checkbox__legend"]])
                ->checkbox(['class' => "checkbox__input"]);
            ?>
        </fieldset>
        <?php
            echo $form->field($model, 'name')->input('search', ['class' => 'input-middle input'])
                ->label('Поиск по имени', ['class' => 'search-task__name']);

            echo Html::submitButton('Искать', ['class' => 'button']);
            ActiveForm::end();
        ?>
    </div>
</section>