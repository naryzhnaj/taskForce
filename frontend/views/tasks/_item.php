<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<div class="new-task__card">
    <div class="new-task__title">
        <a href="<?=Url::toRoute(['tasks/view', 'id' => $model->id]); ?>" class="link-regular"><h2><?=Html::encode($model->title); ?></h2></a>
        <a class="new-task__type link-regular" href="#"><p><?=$model->category->title; ?></p></a>
    </div>
    <div class="new-task__icon new-task__icon--<?=$model->category->icon; ?>"></div>
    <p class="new-task_description"><?=Html::encode($model->description); ?></p>
    <?php if (isset($model->budget)):?>
        <b class="new-task__price new-task__price--<?=$model->category->icon; ?>">
            <?=Html::encode($model->budget); ?><b>₽</b>
        </b>
    <?php endif; ?>
    <?php if (isset($model->address)):?>
        <p class="new-task__place"><?=Html::encode($model->address); ?></p>
    <?php endif; ?>
    <span class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($model->dt_add); ?></span>
</div>
