<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Registration */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="registration-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'student_num')->textInput(['maxlength' => true]) ?>

    <!--?= $form->field($model, 'checked_in')->textInput() ?-->
    <?php
        echo $form->field($model, 'checked_in')->widget(Select2::classname(), [
        'data' => $checkin,
        'options' => ['placeholder' => 'Check In Now?'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
    
    <?php
        echo $form->field($model, 'gender')->widget(Select2::classname(), [
        'data' => $genderTypes,
        'options' => ['placeholder' => 'Select Gender'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
    
    <!--?= $form->field($model, 'gender')->widget(Select2::classname(), [
                    'data' => $genderTypes,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Gender'],
//                    'pluginOptions' => [
//                        'allowClear' => true
//                    ],
                ]); ?-->

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
