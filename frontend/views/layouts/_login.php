<?php
/**
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
         'id' => 'login-form',
         'enableClientValidation' => true,
         'enableAjaxValidation' => true,
         'validateOnSubmit' => true,
         'options' => [
            'method' => 'post',
         ],
         'fieldConfig' => [
            'template' => '{label}<br>{input}<br>{error}',
            'labelOptions' => ['class' => 'form-modal-description'],
            'options' => ['tag' => false],
         ],
      ]);

      echo $form->field($model, 'email')
         ->input('email', ['class' => 'input input-middle', 'autofocus' => true]);

      echo $form->field($model, 'password')->passwordInput(['class' => 'input input-middle']);

      echo Html::submitButton('Войти', ['class' => 'button']);
      ActiveForm::end();
   ?>
   <button class="form-modal-close" type="button">Закрыть</button>
</section>