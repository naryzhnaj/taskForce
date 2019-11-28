<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$now = new DateTime();
$this->title = 'Новые задания';
?>
<div class="page-container">
  <section class="new-task">
      <div class="new-task__wrapper">
          <h1>Новые задания</h1>
          <?php
            if (!$tasks) echo 'Извините, ничего не найдено';
            foreach ($tasks as $task): ?>
              <div class="new-task__card">
                  <div class="new-task__title">
                      <a href="#" class="link-regular"><h2><?=Html::encode($task->title); ?></h2></a>
                      <a class="new-task__type link-regular" href="#"><p><?=Html::encode($task->category->title); ?></p></a>
                  </div>
                  <div class="new-task__icon new-task__icon--<?=Html::encode($task->category->icon); ?>"></div>
                  <p class="new-task_description"><?=Html::encode($task->description); ?></p>
                  <b class="new-task__price new-task__price--<?=Html::encode($task->category->icon); ?>"><?=Html::encode($task->budget); ?>₽</b>
                  <?php if (isset($task->address)):?>
                      <p class="new-task__place"><?=Html::encode($task->address); ?></p>
                  <?php endif;?>
                  <span class="new-task__time"><?=$now->diff(new DateTime($task->dt_add))->format('%d дней %h часов назад'); ?></span>
              </div>
          <?php endforeach; ?>
      </div>
      <div class="new-task__pagination">
          <ul class="new-task__pagination-list">
              <li class="pagination__item"><a href="#"></a></li>
              <li class="pagination__item pagination__item--current">
                  <a>1</a></li>
              <li class="pagination__item"><a href="#">2</a></li>
              <li class="pagination__item"><a href="#">3</a></li>
              <li class="pagination__item"><a href="#"></a></li>
          </ul>
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
                      'class' => 'search-task__form'
            ]]); ?>
              <fieldset class="search-task__categories">
                  <legend>Категории</legend>
                  <?= $form->field($model, 'categories')
                      ->checkboxList($all_categories, ['itemOptions' => ['class' => 'checkbox__input']])
                      ->label(false); ?>
              </fieldset>
              <fieldset class="search-task__categories">
                  <legend>Дополнительно</legend>
                  <?php
                    echo $form->field($model, 'same_city')->checkbox(['class' => 'checkbox__input']);
                    echo $form->field($model, 'is_distant')->checkbox(['class' => 'checkbox__input']);
                  ?>
              </fieldset>
              <?php
                echo $form->field($model, 'period')->dropDownList([
                      'month' => 'За месяц',
                      'week' => 'За неделю',
                      'day' => 'За день'],
                      ['class' => 'multiple-select input'])
                      ->label('Период', ['class' => 'search-task__name']);
                echo $form->field($model, 'title')->input('search', ['class' => 'input-middle input'])
                    ->label('Поиск по названию', ['class' => 'search-task__name']);

                echo Html::submitButton('Искать', ['class' => 'button']);
                ActiveForm::end();
            ?>
      </div>
  </section>
</div>
