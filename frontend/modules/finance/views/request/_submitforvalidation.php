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
      <h1>Submit for Validation</h1>    
        <p class="md-info">This request has been verified and will be submitted for Validation.<br/>This action will notify the <span class="badge btn-info">Head of the Requesting Unit</span>.<br/><br/>Note: Updates on this request will sent via email.</p>
    </div>
    
    <div class="form-group">
        <center><?= Html::submitButton('Confirm Submission', ['class' => 'btn btn-primary']) ?></center>
    </div>

    <?php ActiveForm::end(); ?>

</div>
