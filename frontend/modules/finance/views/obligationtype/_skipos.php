<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

use common\models\procurement\Expenditureclass;
/* @var $form yii\widgets\ActiveForm */
?>

<div class="obligation-type">
    
    <?php
        $form = ActiveForm::begin([
                    'options' => [
                        'id' => 'skip-os'
                    ]
        ]);
    ?>
    
    <!--?= $form->field($model, 'status_id')->textInput() ?-->
    <div class="container">
          <h1>Obligation Requests</h1>    
        
        <div class="row">
            <div class="col-md-4">
            <?= $form->field($model, 'classId')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Expenditureclass::find()->orderBy(['expenditure_class_id'=>SORT_ASC])->all(),'expenditure_class_id','name'),
                    'language' => 'en',
                    //'disabled' => true,
                    //'id' => 'classId',
                    //'options' => ['placeholder' => 'Select Expenditure Object','readonly'=>'readonly'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label('Expenditure Class'); ?>
            </div>
        </div>
        
        <div class="row">
        <div class="col-md-12">
            <div class="input-group">
                <p>This action will reserve skip the OS Number: <span class="badge btn-info"><?= $last_OS ?></span> in the system</p> 
            </div>
        </div>
    </div>
    <br/>
        
    </div>
    

        <div class="form-group">
            <center><?= Html::submitButton('PROCEED', ['class' => 'btn btn-success', 'id'=> 'btnProceed']) ?></center>
        </div>
    

    <?php ActiveForm::end(); ?>

</div>