<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use yii\widgets\Pjax;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Lineitembudget */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lineitembudget-form">

    <?php $form = ActiveForm::begin(['id'=>$model->formName()]); ?>

    <?= $form->field($model, 'division_id')->widget(Select2::classname(), [
        'data' => $listDivisions,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Division'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>
    
    <?= $form->field($model, 'section_id')->widget(Select2::classname(), [
                    'data' => $listSections,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Section'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>            
                
    <?= $form->field($model, 'type_id')->widget(Select2::classname(), [
                    'data' => $listTypes,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select LIB Type'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
    
    <?= $form->field($model, 'subtype_id')->widget(Select2::classname(), [
                    'data' => $listSubTypes,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Sub Type'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
    
    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'period')->textInput() ?>
    
    <?= $form->field($model, 'duration_start')->input('date') ?>

    <?= $form->field($model, 'duration_end')->input('date') ?>
    
    <?php //$form->field($model, 'project_id')->textInput() ?>

    <?= $form->field($model, 'project_id')->widget(Select2::classname(), [
        'data' => $listProject,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Project'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>


    <?= $form->field($model, 'program_id')->widget(Select2::classname(), [
        'data' => $listProgram,
        'language' => 'en',
        'options' => ['placeholder' => 'Select Program'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?php //$form->field($model, 'program_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>