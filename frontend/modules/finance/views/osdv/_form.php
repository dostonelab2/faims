<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use kartik\detail\DetailView;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\helpers\Url;

use common\models\cashier\Creditor;
use common\models\finance\Request;
use common\models\procurement\Expenditureclass;
use common\models\finance\Obligationtype;
//use common\models\procurement\Type;
/* @var $this yii\web\View */
/* @var $model common\models\finance\Request */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="osdv-form">

    <?php $form = ActiveForm::begin(); ?>

    <!--?= $form->field($model, 'request_number')->textInput() ?-->

    <!--?= $form->field($model, 'request_date')->textInput() ?-->
    
    <div class="row">
        <div class="col-md-6"> 
                <?= $form->field($model, 'request_id')->widget(Select2::classname(), [
                    'data' => $requests,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Request','readonly'=>'readonly'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'pluginEvents' => [
                        "change" => "function() {
                            var requestId = $(this).val();
                            $.ajax({
                                url: '".Url::toRoute("/finance/osdv/getrequest")."',
                                //dataType: 'json',
                                method: 'GET',
                                data: {id:requestId},
                                success: function (data, textStatus, jqXHR) {
                                    //$('.image-loader').removeClass( \"img-loader\" );
                                    $('#request').html(data);
                                    $('#osdv-payroll').val($('#request-payroll').val());
                                },
                                beforeSend: function (xhr) {
                                    //$('.image-loader').addClass( \"img-loader\" );
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    //console.log(textStatus+' '+errorThrown);
                                }
                            });
                        }",
                    ],
                ])->label(''); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'create_date')->widget(DateTimePicker::classname(), [
                'readonly' => true,
                //'disabled' => true,
                'options' => ['placeholder' => 'Select Date'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii:ss'
                ]
            ])->label('');?>
        </div>
    </div>
    
    <div id="request" style="padding:0px!important;">  

    </div>
    
    
    <?= $form->field($model, 'type_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Obligationtype::find()->orderBy(['type_id'=>SORT_ASC])->all(),'type_id','name'),
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Source of Fund','readonly'=>'readonly'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    'pluginEvents' => [
                        "change" => "function() {
                            var typeId = $(this).val();
                            if (typeId > 1){
                                //$('.field-osdv-expenditure_class_id').prop('disabled', false);
                                //$('.field-osdv-expenditure_class_id').hide();
                            } else {
                                //$('.field-osdv-expenditure_class_id').prop('disabled', true);
                                //$('.field-osdv-expenditure_class_id').show();
                            }
                        }",
                    ],
                ])->label('Fund Source'); ?>
                
    <?= $form->field($model, 'expenditure_class_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Expenditureclass::find()->orderBy(['expenditure_class_id'=>SORT_ASC])->all(),'expenditure_class_id','name'),
                    'language' => 'en',
                    //'disabled' => true,
                    //'id' => 'expenditure_class_id',
                    'options' => ['placeholder' => 'Select Expenditure Object','readonly'=>'readonly'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label('Expenditure Class'); ?>
                
    <?= $form->field($model, 'payroll')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 
                                'id'=>'submitOsdv',
                                'style'=>'text-align: center;', 
                                ]) ?>
            <button class="btn btn-success" type="button" disabled style="display: none;" id="spinner">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Saving. Please wait . . . 
            </button>

    </div>



    <?php ActiveForm::end(); ?>

</div>


<script>
$( document ).ready(function() {
    //$('#Osdv-expenditure_class_id').select2({disabled:readonly});
    //$('.field-osdv-expenditure_class_id').hide();
});

$(".osdv-form").on("beforeSubmit",function(e){
    e.preventDefault();
    $("#submitOsdv").css({pointerEvents:'none'});
    return true;
});

</script>