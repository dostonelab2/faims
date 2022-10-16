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
                        'id' => 'submit-request'
                    ]
        ]);
    ?>
    
    <!--?= $form->field($model, 'status_id')->textInput() ?-->
    <div class="container">
      <h1>Certify Allotment is Available</h1>    
        <p class="md-info">This Allotment is Certified Available and will be submitted for Disbursement.<br/>This action will notify the <span class="badge btn-info">Accounting Unit</span>.<br/><br/>Note: Updates on this request will sent via email.</p>
    </div>
    
    <div class="form-group">
        <center><?= Html::submitButton('Confirm Obligation', ['class' => 'btn btn-info']) ?></center>
    </div>

    <?php ActiveForm::end(); ?>

</div>