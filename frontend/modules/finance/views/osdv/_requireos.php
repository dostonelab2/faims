<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use kartik\select2\Select2;

/* @var $form yii\widgets\ActiveForm */
?>

<div class="submit-not-eligible">
    
    <?php
        $form = ActiveForm::begin([
                    'options' => [
                        'id' => 'required-allotment'
                    ]
        ]);
    ?>
    
    <!--?= $form->field($model, 'status_id')->textInput() ?-->
    <div class="container">
      <h1>Allocation Required</h1>    
      <p class="md-info">Please add Allotment Items before generating OS Number.</p>
    </div>
    
    <div class="form-group">
        <center><?= Html::Button('OK', ['class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { $("#modalContainer").modal("hide");; })();']) ?></center>
    </div>

    <?php ActiveForm::end(); ?>

</div>
