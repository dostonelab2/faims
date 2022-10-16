<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use kartik\select2\Select2;
use common\models\finance\Dv;
/* @var $form yii\widgets\ActiveForm */
?>

<div class="submit-request">
    
    <?php
        $form = ActiveForm::begin([
                    'options' => [
                        'id' => 'generate-dvnumber'
                    ]
        ]);
    ?>
    
    <!--?= $form->field($model, 'status_id')->textInput() ?-->
    <div class="container">
      <h1>Generate DV Number</h1>
        
        <p class="md-info">This will assign the DV Number : <span class="label label-warning"><?= Dv::generateDvNumber($model->request->obligation_type_id,$model->expenditure_class_id,date("Y-m-d H:i:s")); ?></span> to this Financial Request.
        <br/>This action will notify the <span class="badge btn-info">Accountant</span>.</p>
        
        <br/>Note: Updates on this request will sent via email.
    </div>
    
    <div class="form-group">
        <center>
               <?= Html::Button('Cancel', ['class' => 'btn btn-warning', 'onclick' => '(function ( $event ) { $("#modalContainer").modal("hide");; })();']) ?>
               <?= Html::submitButton('Generate', ['class' => 'btn btn-success']) ?>
        </center>
    </div>

    <?php ActiveForm::end(); ?>

</div>