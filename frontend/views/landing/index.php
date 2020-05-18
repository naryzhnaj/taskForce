<?php
/**
 * @var $this yii\web\View
 * @var Tasks        $tasks последние задания
 */
use yii\helpers\Html;
use yii\helpers\StringHelper;

$this->title = 'TaskForse';
$this->registerJsFile('@web/js/main.js', ['position' => \yii\web\VIEW::POS_END]);
?>
<div class="landing-bottom-container">
    <h2>Последние задания на сайте</h2>
    <?php foreach ($tasks as $task): ?>
    <div class="landing-task">
        <div class="landing-task-top task-<?=$task->category->icon; ?>"></div>
        <div class="landing-task-description">
            <h3><a href="#" class="link-regular"><?=Html::encode($task->title); ?></a></h3>
            <p><?=StringHelper::truncate($task->description, 50, '...'); ?></p>
        </div>
        <div class="landing-task-info">
            <div class="task-info-left">
                <p><a href="#" class="link-regular"><?=$task->category->title; ?></a></p>
                <p><?= Yii::$app->formatter->asRelativeTime($task->dt_add); ?></p>
            </div>
            <?php if (isset($task->budget)):?>                
                <span><?=Html::encode($task->budget); ?><b>₽</b></span>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<div class="landing-bottom-container">
    <button type="button" class="button red-button">смотреть все задания</button>
</div>