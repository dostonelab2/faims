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
                        'id' => 'approve-request'
                    ]
        ]);
    ?>
    
    <!--?= $form->field($model, 'status_id')->textInput() ?-->
    <div class="container">
      <h1>Approve for Disbursement</h1>    
        <p class="md-info">This Request is Approved and will be submitted for Disbursement.<br/>This action will notify the <span class="badge btn-info">Cashiering Unit</span>.<br/><br/>Note: Updates on this request will sent via email.</p>
    </div>
    
    <div class="form-group">
        <center><?= Html::submitButton('APPROVE', ['class' => 'btn btn-success']) ?></center>
    </div>

    <?php ActiveForm::end(); ?>

</div>