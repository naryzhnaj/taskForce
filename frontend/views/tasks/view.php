<?php
/**
 * 
 * @var $task атрибуты задания
 * @var $category    соотв.категория
 * @var $customer    данные заказчика
 * @var $responds    отклики
 */
use yii\helpers\Html;

$this->title = 'Просмотр задания';
const MAX_RATE = 5;
/**
 * отрисовка рейтинга звездочками
 */
function renderStars($rating)
{
    $n = round($rating);

    return str_repeat('<span></span>', $n).str_repeat('<span class="star-disabled"></span>', MAX_RATE - $n);
}
?>
<section class="content-view">
    <div class="content-view__card">
        <div class="content-view__card-wrapper">
            <div class="content-view__header">
                <div class="content-view__headline">
                    <h1><?=Html::encode($task->title); ?></h1>
                    <span>Размещено в категории
                        <a href="#" class="link-regular"><?=Html::encode($category->title); ?></a>
                        <?=Yii::$app->formatter->asRelativeTime($task->dt_add); ?>
                    </span>
                </div>
                <b class="new-task__price new-task__price--<?=Html::encode($category->icon); ?> content-view-price">
                    <?=Html::encode($task->budget); ?><b> ₽</b>
                </b>
                <div class="new-task__icon new-task__icon--<?=Html::encode($category->icon); ?> content-view-icon"></div>
            </div>
            <div class="content-view__description">
                <h3 class="content-view__h3">Общее описание</h3>
                <p><?=Html::encode($task->description); ?></p>
            </div>
            <?php if (isset($task->image)):?>
                <div class="content-view__attach">
                    <h3 class="content-view__h3">Вложения</h3>
                    <a href="#"><?=Html::encode($task->image); ?></a>
                </div>
            <?php endif; ?>
            <?php if (isset($task->address)):?>
                <div class="content-view__location">
                <h3 class="content-view__h3">Расположение</h3>
                <div class="content-view__location-wrapper">
                    <div class="content-view__map">
                        <a href="#"><img src="/img/map.jpg" width="361" height="292"
                                            alt="Москва, Новый арбат, 23 к. 1"></a>
                    </div>
                    <div class="content-view__address">
                        <span class="address__town">Москва</span><br>
                        <span>Новый арбат, 23 к. 1</span>
                        <p>Вход под арку, код домофона 1122</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="content-view__action-buttons">
                <button class=" button button__big-color response-button"
                        type="button">Откликнуться</button>
                <button class="button button__big-color refusal-button"
                        type="button">Отказаться</button>
                <button class="button button__big-color connection-button"
                        type="button">Написать сообщение</button>
        </div>
    </div>
    <div class="content-view__feedback">
        <h2>Отклики <span>(<?=count($responds); ?>)</span></h2>
        <div class="content-view__feedback-wrapper">
            <?php foreach ($responds as $respond): ?>
                <div class="content-view__feedback-card">
                    <div class="feedback-card__top">
                        <a href="#"><img src="/img/man-glasses.jpg" width="55" height="55"></a>
                        <div class="feedback-card__top--name">
                            <p><a href="#" class="link-regular"><?=Html::encode($respond->author->name); ?></a></p>
                            <?=renderStars($respond->author->rating); ?>
                            <b><?=Html::encode($respond->author->rating); ?></b>
                        </div>
                        <span class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($respond->dt_add); ?></span>
                    </div>
                    <div class="feedback-card__content">
                        <p><?=Html::encode($respond->comment); ?></p>
                        <span><?=Html::encode($respond->price); ?> ₽</span>
                    </div>
                    <div class="feedback-card__actions">
                        <button class="button__small-color response-button button"
                                type="button">Откликнуться</button>
                        <button class="button__small-color refusal-button button"
                                type="button">Отказаться</button>
                        <button class="button__chat button"
                                type="button"></button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="connect-desk">
    <div class="connect-desk__profile-mini">
        <div class="profile-mini__wrapper">
            <h3>Заказчик</h3>
            <div class="profile-mini__top">
                <img src="/img/man-brune.jpg" width="62" height="62" alt="Аватар заказчика">
                <div class="profile-mini__name five-stars__rate">
                    <p><?=Html::encode($customer->name); ?></p>
                    <?=renderStars($customer->rating); ?>
                    <b><?=Html::encode($customer->rating); ?></b>
                </div>
            </div>
            <p class="info-customer"><span><?=Html::encode($customer->reviewsAmount); ?> отзывов</span>
                <span class="last-"><?=Html::encode($customer->orders); ?> заказов</span></p>
            <a href="#" class="link-regular">Смотреть профиль</a>
        </div>
    </div>
    <div class="connect-desk__chat">
        <h3>Переписка</h3>
        <div class="chat__overflow">
            <div class="chat__message chat__message--out">
                <p class="chat__message-time">10.05.2019, 14:56</p>
                <p class="chat__message-text">Привет. Во сколько сможешь
                    приступить к работе?</p>
            </div>
            <div class="chat__message chat__message--in">
                <p class="chat__message-time">10.05.2019, 14:57</p>
                <p class="chat__message-text">На задание
                выделены всего сутки, так что через час</p>
            </div>
            <div class="chat__message chat__message--out">
                <p class="chat__message-time">10.05.2019, 14:57</p>
                <p class="chat__message-text">Хорошо. Думаю, мы справимся</p>
            </div>
        </div>
        <p class="chat__your-message">Ваше сообщение</p>
        <form class="chat__form">
            <textarea class="input textarea textarea-chat" rows="2" name="message-text" placeholder="Текст сообщения"></textarea>
            <button class="button chat__button" type="submit">Отправить</button>
        </form>
    </div>
</section>
