<?php
/**
 * @var SignupForm
 * @var $cities    Cities
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
      ], ]);
      echo $form->field($model, 'email')->input('email', ['class' => 'input textarea', 'placeholder' => 'kumarm@mail.ru']);

      echo $form->field($model, 'username', ['template' => '{label}<br>{input}<br>{error}'])
         ->textInput(['class' => 'input textarea', 'placeholder' => 'Мамедов Кумар']);

      echo $form->field($model, 'city')
        ->dropDownList($cities, ['class' => 'multiple-select input town-select registration-town'])
        ->hint('Укажите город, чтобы находить подходящие задачи');

      echo $form->field($model, 'password', ['template' => '{label}<br>{input}<br>{error}'])->passwordInput(['class' => 'input textarea']);

      echo Html::submitButton('Cоздать аккаунт', ['class' => 'button button__registration']);
      ActiveForm::end();
   ?>
   </div>
</section>
