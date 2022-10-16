<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\Ppmp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ppmp-form">
    
    <?php
        $form = ActiveForm::begin([
                    'options' => [
                        'id' => 'ppmp-form'
                    ]
        ]);
    ?>
    
    <!--?= $form->field($model, 'status_id')->textInput() ?-->
    <div class="container">
      <h1>PPMP Approval</h1>    
      <p class="bg-info">Are you sure you want to approve this PPMP?</p>
    </div>
    
    <div class="form-group">
        <center><?= Html::submitButton('APPROVE', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?></center>
    </div>

    <?php ActiveForm::end(); ?>

</div>
