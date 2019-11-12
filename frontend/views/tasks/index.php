<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
$now = new DateTime("now");
$this->title = 'recent tasks';
?>
<div class="page-container">
  <section class="new-task">
      <div class="new-task__wrapper">
          <h1>Новые задания</h1>
          <?php foreach ($allTasks as $task): ?>
          <div class="new-task__card">
              <div class="new-task__title">
                  <a href="#" class="link-regular"><h2><?=Html::encode($task->title); ?></h2></a>
                  <a class="new-task__type link-regular" href="#"><p><?=Html::encode($task->category->title); ?></p></a>
              </div>
              <div class="new-task__icon new-task__icon--clean"></div>
              <p class="new-task_description"><?=Html::encode($task->description); ?></p>
              <b class="new-task__price new-task__price--clean"><?=Html::encode($task->budget); ?><b> ₽</b></b>
              <p class="new-task__place"><?=Html::encode($task->address); ?></p>
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
          <form class="search-task__form" name="test" method="post" action="#">
              <fieldset class="search-task__categories">
                  <legend>Категории</legend>
                  <input class="visually-hidden checkbox__input" id="1" type="checkbox" name="" value="" checked>
                  <label for="1">Курьерские услуги </label>
                  <input class="visually-hidden checkbox__input" id="2" type="checkbox" name="" value="" checked>
                  <label  for="2">Грузоперевозки </label>
                  <input class="visually-hidden checkbox__input" id="3" type="checkbox" name="" value="">
                  <label  for="3">Переводы </label>
                  <input class="visually-hidden checkbox__input" id="4" type="checkbox" name="" value="">
                  <label  for="4">Строительство и ремонт </label>
                  <input class="visually-hidden checkbox__input" id="5" type="checkbox" name="" value="">
                  <label  for="5">Выгул животных </label>
              </fieldset>
              <fieldset class="search-task__categories">
                  <legend>Дополнительно</legend>
                  <input class="visually-hidden checkbox__input" id="6" type="checkbox" name="" value="">
                  <label for="6">Без исполнителя </label>
                  <input class="visually-hidden checkbox__input" id="7" type="checkbox" name="" value="" checked>
                  <label for="7">Удаленная работа </label>
              </fieldset>
              <label class="search-task__name" for="8">Период</label>
                  <select class="multiple-select input" id="8"size="1" name="time[]">
                  <option value="day">За день</option>
                  <option selected value="week">За неделю</option>
                  <option value="month">За месяц</option>
              </select>
              <label class="search-task__name" for="9">Поиск по названию</label>
                  <input class="input-middle input" id="9" type="search" name="q" placeholder="">
              <button class="button" type="submit">Искать</button>
          </form>
      </div>
  </section>
</div>
