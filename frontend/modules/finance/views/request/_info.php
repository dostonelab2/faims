<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $form yii\widgets\ActiveForm */
?>

<div class="attachment-info">
    
    <?php
        $form = ActiveForm::begin([
                    'options' => [
                        'id' => 'attachment-info'
                    ]
        ]);
    ?>
    
    <!--?= $form->field($model, 'status_id')->textInput() ?-->
    <div class="container">
      <h1>Request Attachments</h1>    
        <p class="md-info">Attachments will be the basis for processing this request.<br/>Scanned copy of the Required Documents must be uploaded accordingly in PDF format.</p><br/>
       <ol>
        <?php
            foreach($model->requesttype->requesttypeattachments as $requesttype){
                echo '<li>'.$requesttype->attachment->name.'</li>';
            }
        ?>
        </ol>
      <br/><p>Note: Only requests with complete documents can be submitted for processing.</p>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton('PROCEED', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
