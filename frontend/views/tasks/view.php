<?php
/**
 * @var yii\web\View $this
 * @var Tasks $task        атрибуты задания
 * @var string $action     доступное пользователю действие
 */
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\widgets\RespondWidget;
use frontend\widgets\RatingWidget;

$this->title = 'Просмотр задания';
$this->registerJsFile('@web/js/actionForms.js');
//$this->registerJsFile('@web/js/messenger.js');
$responds = $task->visibleResponds;
$contactPerson = $task->contact;
?>
<section class="content-view">
    <div class="content-view__card">
        <div class="content-view__card-wrapper">
            <div class="content-view__header">
                <div class="content-view__headline">
                    <h1><?=Html::encode($task->title); ?></h1>
                    <span>Размещено в категории
                        <a href="#" class="link-regular"><?=$task->category->title; ?></a>
                        <?=Yii::$app->formatter->asRelativeTime($task->dt_add); ?>
                    </span>
                </div>
                <b class="new-task__price new-task__price--<?=$task->category->icon; ?> content-view-price">
                    <?php if (isset($task->budget)):?>
                        <?=Html::encode($task->budget); ?><b> ₽</b>
                    <?php endif; ?>
                </b>
                <div class="new-task__icon new-task__icon--<?=$task->category->icon; ?> content-view-icon"></div>
            </div>
            <div class="content-view__description">
                <h3 class="content-view__h3">Общее описание</h3>
                <p><?=Html::encode($task->description); ?></p>
            </div>
            <?php if (count($task->attachments)):?>
                <div class="content-view__attach">
                    <h3 class="content-view__h3">Вложения</h3>
                    <?php foreach ($task->attachments as $attachment): ?>
                        <a href="<?=Url::toRoute("@frontend/uploads/$attachment"); ?>"><?=Html::encode($attachment); ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (isset($task->address)):?>
                <div class="content-view__location">
                <h3 class="content-view__h3">Расположение</h3>
                <div class="content-view__location-wrapper">
                    <div class="content-view__map">
                        <a href="#"><img src="/img/map.jpg" width="361" height="292" alt="Москва, Новый арбат, 23 к. 1"></a>
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
            <?= RespondWidget::widget(['action' => $action, 'task_id' => $task->id]); ?>
        </div>
    </div>
        <?php if (count($responds)):?>
        <div class="content-view__feedback">
            <h2>Отклики <span>(<?=count($responds); ?>)</span></h2>
            <div class="content-view__feedback-wrapper">
                <?php foreach ($responds as $respond): ?>
                    <div class="content-view__feedback-card">
                        <div class="feedback-card__top">
                            <a href="#"><img src="/img/man-glasses.jpg" width="55" height="55"></a>
                            <div class="feedback-card__top--name">
                                <p><a href="<?=Url::to(['users/view', 'id' => $respond->author->id]); ?>" class="link-regular"><?=Html::encode($respond->author->name); ?></a></p>
                                <?=RatingWidget::widget(['rating' => $respond->author->rating]); ?>
                                <b><?=$respond->author->rating; ?></b>
                            </div>
                            <span class="new-task__time"><?= Yii::$app->formatter->asRelativeTime($respond->dt_add); ?></span>
                        </div>
                        <div class="feedback-card__content">
                            <p><?=Html::encode($respond->comment); ?></p>
                            <span><?=Html::encode($respond->price); ?> ₽</span>
                        </div>
                        <div class="feedback-card__actions">
                            <a class="button__small-color request-button button" type="button"
                            href="<?=Url::to(['tasks/agree', 'id' => $respond->id]); ?>">Подтвердить</a>
                            <a class="button__small-color refusal-button button" type="button"
                            href="<?=Url::to(['tasks/ignore', 'id' => $respond->id]); ?>">Отказать</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
<section class="connect-desk">
    <div class="connect-desk__profile-mini">
        <div class="profile-mini__wrapper">
            <h3><?=($contactPerson->id === $task->author_id) ? 'Заказчик' : 'Исполнитель';?></h3>
            <div class="profile-mini__top">
                <img src="/img/man-brune.jpg" width="62" height="62" alt="Аватар заказчика">
                <div class="profile-mini__name five-stars__rate">
                    <p><?=Html::encode($contactPerson->name); ?></p>
                </div>
            </div>
            <p class="info-customer"><span><?=$contactPerson->orders; ?> отзывов</span>
                <span class="last-"><?=Yii::$app->formatter->asRelativeTime($contactPerson->dt_add); ?> на сайте</span></p>
            <a href="<?=Url::toRoute(['users/view', 'id' => $contactPerson->id]); ?>" class="link-regular">Смотреть профиль</a>
        </div>
    </div>
    <div id="chat-container">
    <!--добавьте сюда атрибут task с указанием в нем id текущего задания-->
        <chat class="connect-desk__chat"></chat>
    </div>
</section>