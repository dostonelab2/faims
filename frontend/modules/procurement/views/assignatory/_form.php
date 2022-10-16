<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Assignatory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="assignatory-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'report_title')->textInput(['maxlength' => true])
    ?>

    <?= $form->field($model, 'CompanyTitle')->textInput(['maxlength' => true])
    ?>

    <?= $form->field($model, 'RegionOffice')->textInput(['maxlength' => true])
    ?>

    <?= $form->field($model, 'Address')->textInput(['maxlength' => true])
    ?>

    <?=
    $form->field($model, 'assignatory_1')->widget(Select2::classname(), [
        'data' => $listEmployee,
        'options' => ['placeholder' => 'Select Assignatory'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
    
    <?=
    $form->field($model, 'assignatory_2')->widget(Select2::classname(), [
        'data' => $listEmployee,
        'options' => ['placeholder' => 'Select Assignatory'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
    
    <?=
    $form->field($model, 'assignatory_3')->widget(Select2::classname(), [
        'data' => $listEmployee,
        'options' => ['placeholder' => 'Select Assignatory'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
    

    <?=
    $form->field($model, 'assignatory_4')->widget(Select2::classname(), [
        'data' => $listEmployee,
        'options' => ['placeholder' => 'Select Assignatory'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
    
    <?=
    $form->field($model, 'assignatory_5')->widget(Select2::classname(), [
        'data' => $listEmployee,
        'options' => ['placeholder' => 'Select Assignatory'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
    
    <?=
    $form->field($model, 'assignatory_6')->widget(Select2::classname(), [
        'data' => $listEmployee,
        'options' => ['placeholder' => 'Select Assignatory'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
