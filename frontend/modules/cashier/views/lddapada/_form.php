<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\date\DatePicker;
use kartik\select2\Select2;
use common\models\cashier\Lddapada;
use common\models\finance\Obligationtype;
/* @var $this yii\web\View */
/* @var $model common\models\cashier\Lddapada */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lddapada-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'type_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Obligationtype::find()->all(),'type_id','name'),
                    'language' => 'en',
                    //'theme' => Select2::THEME_DEFAULT,`
                    'options' => [
                        'placeholder' => 'Select Request Type',
                        'readonly'=>'readonly',
                        'options' => [
                            2 => ['disabled' => true],
                            3 => ['disabled' => true],
                        ],
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'options' => [
                            2 => ['disabled' => true],
                            3 => ['disabled' => true],
                        ],
                    ],
//                    'options' => [
//                        2 => ['disabled' => true],
//                        3 => ['disabled' => true],
//                    ],
                    'pluginEvents'=>[
                        "change" => 'function() { 
                            var typeId=this.value;
                            $.post("/cashier/lddapada/batchnumber", 
                                {
                                    typeId: typeId
                                }, 
                                function(response){
                                    if(response){
                                       $("#lddapada-batch_number").val(response);
                                       //alert(response);
                                    }
                                }
                            );
                        }
                    ',]
                ])->label('Fund Source'); ?>
                
    <?= $form->field($model, 'batch_number')->textInput(['value' => Lddapada::generateBatchNumber(),'maxlength' => true]) ?>

    <?= DatePicker::widget([
            'model' => $model, 
            'attribute' => 'batch_date',
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'options' => ['placeholder' => 'Set Batch Date', 'value' => date("Y-m-d",strtotime("now"))],
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => "yyyy-mm-dd",
            ]
        ]); ?>
    
    
    
    <?= $form->field($model, 'certified_correct_id')->hiddenInput(['value' => $signatories->assignatory_1])->label(false) ?>

    <?= $form->field($model, 'approved_id')->hiddenInput(['value' => $signatories->assignatory_2])->label(false) ?>

    <?= $form->field($model, 'validated1_id')->hiddenInput(['value' => $signatories->assignatory_3])->label(false) ?>

    <?= $form->field($model, 'validated2_id')->hiddenInput(['value' => $signatories->assignatory_4])->label(false) ?>
    
    <?= $form->field($model, 'created_by')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
