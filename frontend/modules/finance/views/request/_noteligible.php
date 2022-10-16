<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $form yii\widgets\ActiveForm */
?>

<div class="submit-not-eligible">
    
    <?php
        $form = ActiveForm::begin([
                    'options' => [
                        'id' => 'submit-not-eligible'
                    ]
        ]);
    ?>
    
    <!--?= $form->field($model, 'status_id')->textInput() ?-->
    <div class="container">
      <h1>Not Eligible for Submission</h1>    
      <p class="md-info">This request is not yet eligible for submission.<br/>Kindly attach all required documents and send the original copies to the Accounting Unit.<br/><br/>Note: The <b>Submit</b> button will be <span class="badge btn-success">Green</span> once all attachments are complete.</p>
    </div>
    
    <div class="form-group">
        <center><?= Html::Button('OK', ['class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { $("#modalContainer").modal("hide");; })();']) ?></center>
    </div>

    <?php ActiveForm::end(); ?>

</div>
