<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use kartik\select2\Select2;
use kartik\widgets\DatePicker;

/* @var $form yii\widgets\ActiveForm */
?>

<div class="submit-request">
    
    <?php
        $form = ActiveForm::begin([
                    'options' => [
                        'id' => 'reassign-os'
                    ]
        ]);
    ?>
    <!--?= $form->field($model, 'status_id')->textInput() ?-->

        <h3>Reassign OS Number</h3> 
        
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, '_osdv_id')->textInput(['value'=>$modelOsdv->osdv_id, 'readonly'=>true])->label('OSDV ID') ?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, '_request_id')->textInput(['value'=>$modelOsdv->request_id, 'readonly'=>true])->label('Request ID') ?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, '_os_number')->textInput(['value'=>$modelOsdv->os->os_number, 'readonly' => true])->label('Old OS Number') ?>
            </div>
        </div> 
        
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'os_number')->textInput()->label('New OS Number') ?>
            </div>
        </div> 
    
        <div class="row">
            <div class="col-md-12">         
                <?= $form->field($model, 'os_date')->widget(DatePicker::classname(), [
                    'readonly' => false,
                    //'disabled' => true,
                    'options' => ['placeholder' => 'Select Date'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ])->label('OS Date');?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Confirm New OS Number', ['class' => 'btn btn-info', 'data-confirm' => 'Are you sure you want to update the OS NUMBER?']) ?>
        </div>
           
            
    <?php ActiveForm::end(); ?>

</div>