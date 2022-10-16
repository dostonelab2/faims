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
                        'id' => 'save-lddapada'
                    ]
        ]);
    ?>
    
    <!--?= $form->field($model, 'status_id')->textInput() ?-->
    <div class="container">
        <p class="md-info">Do you want to save changes?<br/>
    </div>
    
    <div class="form-group">
        <center>
               <?= Html::Button('Cancel', ['class' => 'btn btn-warning', 'onclick' => '(function ( $event ) { $("#modalContainer").modal("hide");; })();']) ?>
               <?= Html::submitButton('OK', ['class' => 'btn btn-success']) ?>
        </center>
    </div>

    <?php ActiveForm::end(); ?>

</div>
