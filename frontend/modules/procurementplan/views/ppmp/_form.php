<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\Ppmp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ppmp-form">
    
    <?php
        $form = ActiveForm::begin([
                    'options' => [
                        'id' => 'ppmp-form'
                    ]
        ]);
    ?>
    
    <?= $form->field($model, 'year')->dropDownList($listYear, ['prompt'=>'Select Year']); ?>
    
    <?= $form->field($model, 'division_id')->widget(Select2::classname(), [
        'data' => $listDivisions,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Division'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>
    
    <?= $form->field($model, 'unit_id')->widget(Select2::classname(), [
                    'data' => $listUnits,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Unit'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?> 

    <?= $form->field($model, 'project_id')->textInput() ?>

    <?= $form->field($model, 'end_user_id')->textInput() ?>

    <?= $form->field($model, 'head_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
