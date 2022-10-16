<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\helpers\Url;

use common\models\finance\Request;
use common\models\finance\Requesttype;
/* @var $this yii\web\View */
/* @var $model common\models\finance\Osdv */
/* @var $form yii\widgets\ActiveForm */
?>
<pre><?php 
    //print_r(Request::find()->where('status_id =:status_id',[':status_id'=>40])->all());
    //print_r(Requesttype::find()->all());
    ?></pre>
<div class="osdv-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'request_id')->textInput() ?>
    <div class="row">
        <div class="col-md-6"> 
                <?= $form->field($model, 'request_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Requesttype::find()->where('active =:active',[':active'=>1])->all(),'request_type_id','name'),
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Request Type','readonly'=>'readonly'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    'pluginEvents'=>[
                        "change" => 'function() { 
                            var requestTypeId=this.value;
                            $.post("/finance/request/updateparticulars/", 
                                {
                                    requestTypeId: requestTypeId
                                }, 
                                function(response){
                                    if(response){
                                       $("#request-particulars").val(response.default_text);
                                       //alert(response.default_text);
                                    }
                                }
                            );
                        }
                    ',]
                ])->label('Request Type'); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'type_id')->widget(DateTimePicker::classname(), [
                'readonly' => true,
                'disabled' => true,
                'options' => ['placeholder' => 'Select Date'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii:ss'
                ]
            ])->label('Request Date');?>
        </div>
        
    </div>
                <?php /*echo $form->field($model, 'request_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Request::find()->where('status_id =:status_id',[':status_id'=>40])->all(),'request_id','request_number'),
                    //'data' => ArrayHelper::map(Request::findAll(),'request_id','request_id'),
                    //$units = Unit::find()->orderBy('name')->asArray()->all();
                    //$listUnits = ArrayHelper::map($units, 'unit_id', 'name');
                    'options' => ['placeholder' => 'Select Request ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);*/ ?>
    </div>

    <?= $form->field($model, 'type_id')->textInput() ?>

    <?= $form->field($model, 'expenditure_class_id')->textInput() ?>

    <?= $form->field($model, 'status_id')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
