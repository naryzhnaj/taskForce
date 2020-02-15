<?php
/**
 * 
 * @var ActiveForm $form
 * @var LoginForm  $model
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
<section class="modal enter-form form-modal" id="enter-form">
   <h2>Вход на сайт</h2>
   <?php
   $form = ActiveForm::begin([
      'enableClientValidation' => true,
      'validateOnSubmit' => true,
      'options' => [
         'method' => 'post',
      ],
      'fieldConfig' => ['labelOptions' => ['class' => 'form-modal-description']],
      ]);
   ?>
   <?=$form->field($model, 'email')->input('email', ['class'=>'input input-middle']); ?>
   <?=$form->field($model, 'password')->passwordInput(['class'=>'input input-middle']); ?>
   <?php
      echo Html::submitButton('Войти', ['class' => 'button']);
      ActiveForm::end();
   ?>
   <button class="form-modal-close" id="close-modal" type="button">Закрыть</button>
</section>