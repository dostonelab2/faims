<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use kartik\select2\Select2;
use common\models\cashier\Checknumber;

/* @var $form yii\widgets\ActiveForm */
?>

<div class="submit-request">
    
    <?php
        $form = ActiveForm::begin([
                    'options' => [
                        'id' => 'assign-check'
                    ]
        ]);
        $year = date("Y");
        $month = date("m");
    ?>
    
    <?= $form->field($model, 'check_number')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'selected_keys')->hiddenInput()->label(false) ?>
    <div class="container">
        <p class="md-info">Assign Check Number <span class="label label-success" style="font-size: medium;"><?php echo $model->check_number; ?></span> to the selected items?<br/>
        <p id="keys"></p>
    </div>
    
    <div class="form-group">
        <center>
               <?= Html::Button('Cancel', ['class' => 'btn btn-warning', 'onclick' => '(function ( $event ) { $("#modalPreview").modal("hide"); })();']) ?>
               <?= Html::submitButton('OK', ['class' => 'btn btn-success', 'id'=>'btnOK']) ?>
        </center>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
$(document).ready(function(){
    var keys = $("#lddap-ada-items").yiiGridView("getSelectedRows");
    $("#checknumber-selected_keys").val(keys);
    
    if(keys.length == 0){
        $('#btnOK').prop("disabled",true);
    }
});
</script>
