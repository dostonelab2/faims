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
                        'id' => 'not-eligible-for-validation'
                    ]
        ]);
    ?>
    
    <!--?= $form->field($model, 'status_id')->textInput() ?-->
    <div class="container">
      <h1>Not Eligible for Validation</h1>    
      <p class="md-info">This request is not yet eligible for validation.<br/>Kindly mark all attachements are Verified.<br/></p>
    </div>
    
    <div class="form-group">
        <center><?= Html::Button('OK', ['class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { $("#modalContainer").modal("hide");; })();']) ?></center>
    </div>

    <?php ActiveForm::end(); ?>

</div>
