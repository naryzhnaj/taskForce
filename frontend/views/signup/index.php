<?php
/**
 * @var yii\web\View
 * @var ActiveForm   $form
 * @var SignupForm   $model
 * @var Cities       $cities
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Регистрация';
?>
<section class="registration__user">
   <h1>Регистрация аккаунта</h1>
   <div class="registration-wrapper">
   <?php
      $form = ActiveForm::begin([
         'id' => 'signup',
         'enableClientValidation' => true,
         'validateOnSubmit' => true,
         'validateOnChange' => true,
         'options' => [
            'method' => 'post',
            'class' => 'registration__user-form form-create',
         ],
         'fieldConfig' => ['template' => '{label}<br>{input}<br>{error}'],
      ]);

      echo $form->field($model, 'email')->textarea(['class' => 'input textarea', 'rows' => 1, 'placeholder' => 'kumarm@mail.ru']);

      echo $form->field($model, 'username')->textarea(['class' => 'input textarea', 'rows' => 1, 'placeholder' => 'Мамедов Кумар']);

      echo $form->field($model, 'city')
        ->dropDownList($cities, ['class' => 'multiple-select input town-select registration-town'])
        ->hint('Укажите город, чтобы находить подходящие задачи');

      echo $form->field($model, 'password')->passwordInput(['class' => 'input textarea']);

      echo Html::submitButton('Cоздать аккаунт', ['class' => 'button button__registration']);
      ActiveForm::end();
   ?>
   </div>
</section>
<?php $this->beginBlock('woman'); ?>
   <div class="clipart-woman">
      <img src="./img/clipart-woman.png" width="238" height="450">
   </div>
   <div class="clipart-message">
      <div class="clipart-message-text">
      <h2>Знаете ли вы, что?</h2>
      <p>После регистрации вам будет доступно более
         двух тысяч заданий из двадцати разных категорий.</p>
         <p>В среднем, наши исполнители зарабатывают
         от 500 рублей в час.</p>
      </div>
   </div>
<?php $this->endBlock(); ?>