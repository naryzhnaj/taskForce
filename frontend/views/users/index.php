<?php
/**
 * @var yii\web\View       $this
 * @var ActiveForm         $form
 * @var UserSearchForm     $model
 * @var ActiveDataProvider $dataProvider
 * @var array              $categories список категорий
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
    return '<div class="form-group">'
        .Html::checkbox($name, $checked, ['value' => $value, 'id' => $index, 'class' => 'visually-hidden checkbox__input'])
        .Html::label($label, $index).'</div>';
};

?>
<section class="user__search">
    <div class="user__search-link">
        <p>Сортировать по:</p>
        <ul class="user__search-list">
            <li class="user__search-item">
                <a href="<?=Url::toRoute(['', 'sort' => 'rating']); ?>" class="link-regular">Рейтингу</a>
            </li>
            <li class="user__search-item">
                <a href="<?=Url::toRoute(['', 'sort' => 'orders']); ?>" class="link-regular">Числу заказов</a>
            </li>
            <li class="user__search-item">
                <a href="<?=Url::toRoute(['', 'sort' => 'popularity']); ?>" class="link-regular">Популярности</a>
            </li>
        </ul>
    </div>
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
                echo $form->field($model, 'is_free', ['template' => '{input}{label}'])->
                   checkbox(['class' => 'visually-hidden checkbox__input'], false);
                echo $form->field($model, 'is_online', ['template' => '{input}{label}'])->
                   checkbox(['class' => 'visually-hidden checkbox__input'], false);
                echo $form->field($model, 'with_reviews', ['template' => '{input}{label}'])->
                    checkbox(['class' => 'visually-hidden checkbox__input'], false);
                echo $form->field($model, 'is_favorite', ['template' => '{input}{label}'])->
                    checkbox(['class' => 'visually-hidden checkbox__input'], false);
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
