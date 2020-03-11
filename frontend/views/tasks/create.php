<?php
/**
 * @var yii\web\View
 * @var ActiveForm     $form
 * @var TaskCreateForm $model
 * @var array          $all_categories список категорий
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Создать задание';
$this->registerJsFile('js/dropzone.js');
?>
<section class="create__task">
   <h1>Публикация нового задания</h1>
   <div class="create__task-main">
      <?php
         $form = ActiveForm::begin([
            'id' => 'task-form',
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'validateOnBlur' => true,
            'validateOnChange' => true,
            'validateOnSubmit' => true,
            'options' => [
               'method' => 'post',
               'class' => 'create__task-form form-create',
               'enctype' => 'multipart/form-data'
            ],
            'fieldConfig' => ['template' => '{label}<br>{input}{hint}{error}'],
         ]);

         echo $form->field($model, 'title')->textarea(['class' => 'input textarea', 'rows' => 1,
             'placeholder' => 'Повесить полку', ])->hint('Кратко опишите суть работы');

         echo $form->field($model, 'description')->textarea(['class' => 'input textarea', 'rows' => 7,
             'placeholder' => 'Place your text', ])
             ->hint('Укажите все пожелания и детали, чтобы исполнителям было проще соориентироваться');

         echo $form->field($model, 'category_id')->dropDownList($all_categories,
             ['class' => 'multiple-select input multiple-select-big'])->hint('Выберите категорию');
      ?>
     <label>Файлы</label> 
      <span>Загрузите файлы, которые помогут исполнителю лучше выполнить или оценить работу</span>
      <div class="create__file">
         <span>Добавить новый файл</span>
         <?= $form->field($model, 'files[]', ['template' => '{input}{error}'])->fileInput(['multiple' => true]);?>  
      </div>
            
      <?= $form->field($model, 'location')->
         input('search', ['class' => 'input-middle input input-navigation', 'rows' => 1,
         'placeholder' => 'Санкт-Петербург, Калининский район', ])
         ->hint('Укажите адрес исполнения, если задание требует присутствия');?>

      <div class="create__price-time">
         <div class="create__price-time--wrapper">                  
            <?= $form->field($model, 'budget')->
               textarea(['class' => 'input textarea input-money', 'rows' => 1, 'placeholder' => 1000])
               ->hint('Не заполняйте для оценки исполнителем'); ?>
         </div>
         <div class="create__price-time--wrapper">
            <?= $form->field($model, 'end_date')->
               input('date', ['class' => 'input-middle input input-date', 'rows' => 1, 'placeholder' => '10.11, 15:00'])
               ->hint('Укажите крайний срок исполнения'); ?>
         </div>
      </div>
      <?php
         echo Html::submitButton('Опубликовать', ['class' => 'button']);
         ActiveForm::end();
      ?>
      <div class="create__warnings">
         <div class="warning-item warning-item--advice">
            <h2>Правила хорошего описания</h2>
            <h3>Подробности</h3>
            <p>Друзья, не используйте случайный<br>
               контент – ни наш, ни чей-либо еще. Заполняйте свои
               макеты, вайрфреймы, мокапы и прототипы реальным
               содержимым.</p>
            <h3>Файлы</h3>
            <p>Если загружаете фотографии объекта, то убедитесь,
               что всё в фокусе, а фото показывает объект со всех
               ракурсов.</p>
         </div>

         <?php if ($model->hasErrors()):?>
            <div class="warning-item warning-item--error">
               <h2>Ошибки заполнения формы</h2>
               <?php foreach ($model->errors as $field => $value): ?>
                  <h3><?=Html::encode($field->label); ?></h3>
                  <p><?=Html::encode($value[0]); ?></p>
               <?php endforeach; ?>
            </div>
         <?php endif; ?>
      </div>
   </div>
</section>
<script>
  var dropzone = new Dropzone("div.create__file", {url: "/", paramName: "Attach"});
</script>