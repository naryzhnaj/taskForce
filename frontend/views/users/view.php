<?php
/**
 * @var $this yii\web\View
 * @var $user Users        данные исполнителя
 */
use yii\helpers\Html;
use frontend\widgets\RatingWidget;

$this->title = 'Контакты исполнителя';
$age = (new DateTime('now'))->diff(new DateTime($user->account->birth_date))->format(', %y лет');
?>
<section class="content-view">
    <div class="user__card-wrapper">
        <div class="user__card">
            <img src="/img/man-hat.png" width="120" height="120" alt="Аватар пользователя">
            <div class="content-view__headline">
                <h1><?=Html::encode($user->name); ?></h1>
                <p><?=$user->city->title, $age; ?></p>
                <div class="profile-mini__name five-stars__rate">
                    <?=RatingWidget::widget(['rating' => $user->rating]); ?>
                    <b><?=$user->rating; ?></b>
                </div>
                <b class="done-task">Выполнил <?=$user->orders; ?> заказов</b><b class="done-review">Получил <?=$user->reviewsAmount; ?> отзывов</b>
            </div>
            <div class="content-view__headline user__card-bookmark user__card-bookmark--current">
                <span>Был на сайте 25 минут назад</span>
            </div>
        </div>
        <div class="content-view__description">
            <p><?=Html::encode($user->account->bio); ?></p>
        </div>
        <div class="user__card-general-information">
            <div class="user__card-info">
                <h3 class="content-view__h3">Специализации</h3>
                <div class="link-specialization">
                    <?php foreach ($user->professions as $profession): ?>
                        <a href="#" class="link-regular"><?=$profession; ?></a>
                    <?php endforeach; ?>
                </div>
                <h3 class="content-view__h3">Контакты</h3>
                <div class="user__card-link">
                    <a class="user__card-link--tel link-regular" href="#"><?=Html::encode($user->account->phone); ?></a>
                    <a class="user__card-link--email link-regular" href="mailto:<?=Html::encode($user->email); ?>"><?=Html::encode($user->email); ?></a>
                    <a class="user__card-link--skype link-regular" href="#"><?=Html::encode($user->account->skype); ?></a>
                </div>
            </div>
            <div class="user__card-photo">
                <h3 class="content-view__h3">Фото работ</h3>
                <a href="#"><img src="/img/rome-photo.jpg" width="85" height="86" alt="Фото работы"></a>
                <a href="#"><img src="/img/smartphone-photo.png" width="85" height="86" alt="Фото работы"></a>
                <a href="#"><img src="/img/dotonbori-photo.png" width="85" height="86" alt="Фото работы"></a>
            </div>
        </div>
    </div>
    <div class="content-view__feedback">
        <h2>Отзывы<span>(<?=count($user->reviews); ?>)</span></h2>
        <div class="content-view__feedback-wrapper reviews-wrapper">
            <?php foreach ($user->reviews as $review): ?>
                <div class="feedback-card__reviews">
                    <p class="link-task link">Задание <a href="#" class="link-regular">«<?=Html::encode($review->task->title); ?>»</a></p>
                    <div class="card__review">
                        <a href="#"><img src="/img/man-glasses.jpg" width="55" height="54"></a>
                        <div class="feedback-card__reviews-content">
                            <p class="link-name link"><a href="#" class="link-regular"><?=Html::encode($review->task->author->name); ?></a></p>
                            <p class="review-text"><?=Html::encode($review->comment); ?></p>
                        </div>
                        <div class="card__review-rate">
                            <p class="five-rate big-rate"><?=Html::encode($review->value); ?><span></span></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="connect-desk">
    <div class="connect-desk__chat">
    </div>
</section>
