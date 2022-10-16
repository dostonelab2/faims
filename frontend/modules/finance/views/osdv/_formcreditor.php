<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

use common\models\cashier\Creditor;
/* @var $this yii\web\View */
/* @var $model common\models\cashier\Creditortmp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="creditortmp-form">

    <?php $form = ActiveForm::begin(['id' => 'new-creditor-form']); ?>

    <?= $form->field($model, 'creditor_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Creditor::find(['active'=> 1])->all(),'creditor_id','name'),
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select creditor'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label('Creditor Name'); ?> 
    
    <?= $form->field($model, 'particulars')->textarea(['rows' => 6]) ?>
               
    <?= $form->field($model, 'amount')->textInput() ?>
    
    <?= $form->field($model, 'osdv_id')->textInput() ?>
                
    <div class="form-group text-right">
        <?= Html::submitButton('Add', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
