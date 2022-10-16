<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use kartik\select2\Select2;

/* @var $form yii\widgets\ActiveForm */
?>

<div class="submit-request">
    
    <?php
        $form = ActiveForm::begin([
                    'options' => [
                        'id' => 'validate-request'
                    ]
        ]);
    ?>
    
    <!--?= $form->field($model, 'status_id')->textInput() ?-->
    <div class="container">
      <h1>Request Validation</h1>    
        <p class="md-info">This request will be Validation.<br/>This action will notify the <span class="badge btn-info">Budgeting and/or Acounting units</span>.<br/><br/>Note: Updates on this request will sent via email.</p>
    </div>
    
    <div class="form-group">
        <center>
               <?= Html::Button('Cancel', ['class' => 'btn btn-warning', 'onclick' => '(function ( $event ) { $("#modalContainer").modal("hide");; })();']) ?>
               <?= Html::submitButton('Confirm Validation', ['class' => 'btn btn-success']) ?>
        </center>
    </div>

    <?php ActiveForm::end(); ?>

</div>
