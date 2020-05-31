<?php
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\widgets\RatingWidget;
?>
<div class="content-view__feedback-card user__search-wrapper">
    <div class="feedback-card__top">
        <div class="user__search-icon">
            <a href="#"><img src="/img/man-glasses.jpg" width="65" height="65"></a>
            <span><?=$model->orders; ?> заданий</span>
            <span><?=$model->reviewsAmount; ?> отзывов</span>
        </div>
        <div class="feedback-card__top--name user__search-card">
            <p class="link-name">
                <a href="<?=Url::toRoute(['users/view', 'id' => $model->id]); ?>" class="link-regular"><?=Html::encode($model->name); ?></a>
            </p>
            <?=RatingWidget::widget(['rating' => $model->rating]); ?>
            <b><?=$model->rating; ?></b>
            <p class="user__search-content"><?=Html::encode($model->account->bio); ?></p>
        </div>
        <span class="new-task__time">Был на сайте 25 минут назад</span>
    </div>
    <div class="link-specialization user__search-link--bottom">
        <?php foreach ($model->professions as $profession): ?>
            <a href="#" class="link-regular"><?=$profession; ?></a>
        <?php endforeach; ?>
    </div>
</div>
