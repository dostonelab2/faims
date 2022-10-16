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
                        'id' => 'certify-cash-available'
                    ]
        ]);
    ?>
    
    <!--?= $form->field($model, 'status_id')->textInput() ?-->
    <div class="container">
      <h1>Certify Cash is Available</h1>    
        <p class="md-info">This is Certify the Cash Availability and will be submitted for Approval.<br/>This action will notify the <span class="badge btn-info">Office of the Regional Director</span>.<br/><br/>Note: Updates on this request will sent via email.</p>
    </div>
    
    <div class="form-group">
       <div style="margin-left: 40px;">
        <?= $form->field($model, 'cashAvailable')->checkBox(['label' => '  Cash Available', 'uncheck' => true]); ?>
    
        <?= $form->field($model, 'subjectToAda')->checkBox(['label' => '  Subject to Authority to Debit Account (when applicable)']); ?>

        <?= $form->field($model, 'supportingDocumentsComplete')->checkBox(['label' => '  Supporting documents complete and amount claimed proper']); ?>
        </div>
    </div>
    
    
    <div class="form-group">
        <center><?= Html::submitButton('Certify', ['class' => 'btn btn-info']) ?></center>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<script>

$("#certify-cash-available").submit(function(){ 
    //alert("hahaha");
    
    //return false; 

})
</script>