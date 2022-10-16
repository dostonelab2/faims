<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Obligationrequest */
/* @var $form yii\widgets\ActiveForm */


use dosamigos\ckeditor\CKEditor;

$BaseURL = $GLOBALS['frontend_base_uri'];


if ($model->requested_by=='') {
    $model->requested_by =  $assig->assignatory_1;
}
if ($model->funds_available=='') {
    $model->funds_available =  $assig->assignatory_2;
}

$this->registerJsFile('https://code.jquery.com/ui/1.12.1/jquery-ui.js');
?>


<div class="obligationrequest-form">



    <div class="panel panel-default disabled" id="mPanel">
        <div class="panel-body">

            <!-- Panel Start -->
            <h5><a id="startButton2"  href="javascript:void(0);"><img src="<?= $BaseURL;?>images\help.png" height="52" width="98" style="padding: 10px;"></a></h5>

            <?php   $form = ActiveForm::begin(['id' => 'obligationrequest-form', 'enableClientValidation' => true]); ?>

            <div class="row">
                <div class="col-lg-12" style="padding: 25px;padding-top:5px;padding-bottom: 0px;">

                <div class="col-lg-4">

                    <h5 style='margin:0px;' data-step='6' data-intro='Select OS Type'><span>
                    <?=
                    $form->field($model, 'os_type')->widget(Select2::classname(), [
                        'name' => 'cboOSType',
                        'id'=> 'cboOSType',
                        'hideSearch' => true,
                        'data' => $ostype_data,
                        'options' => [
                            'multiple' => false
                        ],
                        'pluginEvents' => [
                            "change" => "function() {
                                             var data=$(this).val();
                                             if (data=='PS') {
                                                  $('#obligationrequest-purchase_no').prop('disabled',true);
                                             }
                                             if (data=='MOOE1') {
                                                if( $('#chkPO').prop('checked')==true) {
                                                    $('#obligationrequest-purchase_no').prop('disabled',false);
                                                }else{
                                                    $('#obligationrequest-purchase_no').prop('disabled',true);
                                                }
                                             }
                                             if (data == 'CO') {
                                                 $('#obligationrequest-purchase_no').prop('disabled',true);
                                             }
                                    }",
                        ],
                    ])->label('OS Type');
                    ?>
                        </span></h5>

                    </div>


                    <div class="col-lg-4" style="padding: 25px;">
                        <?php
                        // Display widget as a radio control in mini size with custom label style
                        echo SwitchInput::widget([
                            'name' => 'chkPO',
                            'options'=> ['id'=> 'chkPO','disabled'=>true,],
                            'pluginOptions' => [
                                 'size' => 'mini',
                                'handleWidth'=>150,
                                'onText'=>'With P.O',
                                'offText'=>'Without P.O',
                            ],
                            'labelOptions' => ['style' => 'font-size: 12px'],
                            'pluginEvents' => [
                                "switchChange.bootstrapSwitch" => "function() {
                                     var s = $('#obligationrequest-os_type').val();
                                     var d = $('#chkPO').prop('checked');
                                     if (s=='MOOE1') {
                                        if(d==true) {
                                        $('#obligationrequest-purchase_no').prop('disabled',false);
                                        }else{
                                          $('#obligationrequest-purchase_no').val('').trigger('change');
                                          $('#obligationrequest-purchase_no').prop('disabled',true);
                                          $('#obligationrequest-particulars').val ('');
                                          $('#obligationrequest-amount').val('');
                                        }
                                     }else{
                                        $('#obligationrequest-purchase_no').prop('disabled',true);
                                     }
                                    }",
                            ],
                        ]);
                        ?>
                    </div>

                    <div class="col-lg-4">
                        <?= $form->field($model, 'purchase_no')->widget(Select2::classname(), [
                            'data' => $listPono,
                            'id'=> 'cboPono',
                            'name'=> 'cboPono',
                            'language' => 'en',
                            'options' => ['placeholder' => 'Select P.O.','style'=> 'padding-top:0px;','disabled'=> true,
                                'class'=> 'cboPono',],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'dropdownParent'=> new yii\web\JsExpression('$("#modalObligation")')
                            ],
                            'pluginEvents' => [
                                "change" => "
                                function() {
                                             
                                             var po_num=$(this).val();
                                             jQuery.ajax( {
                                                type: \"POST\",
                                                data: {
                                                    po_num: po_num,
                                                },
                                                url: \"/procurement/obligationrequest/checkimportid\",
                                                dataType: \"text\",
                                                success: function ( response ) {                                 
                                                    data = $.parseJSON(response);
                                                    $.each(data, function(i, item) {
                                                        var particular = item.Particulars;
                                                        var amount = item.Amount;
                                                        var payee  = item.Payee;
                                                        var address = item.Address;
                                                        $('#obligationrequest-particulars').val (particular);
                                                       //CKEDITOR.instances.mypt.setData(description);
                                                        $('#obligationrequest-amount').val(amount);
                                                        $('#obligationrequest-payee').val(payee);
                                                        $('#obligationrequest-address').val(address);
                                                    });
                                                },
                                                error: function ( xhr, ajaxOptions, thrownError ) {
                                                    alert( thrownError );
                                                }
                                            });
                                         }",
                            ],


                        ])->label(''); ?>
                    </div>

                </div>
            </div>

            <div class="space-20"></div>

            <div class="row">

                <div class="col-lg-12">

                    <div class="col-lg-3">
                        <?= $form->field($model, 'payee')->textInput(['maxlength' => true,'placeholder'=>'John Doe']) ?>
                        <?= $form->field($model, 'office')->textInput(['maxlength' => true,'placeholder'=>'Department Of Science and Technology']) ?>
                    </div>
                    <div class="col-lg-3">
                        <?= $form->field($model, 'os_no') ->textInput(['placeholder'=>'<AutoGenerated>','readonly'=>'true','tabindex'=>'-1'])->label('OS # :')?>
                        <?= $form->field($model, 'os_date')->input("date",['value' =>  date("Y-m-d")])->label('Date. ')?>
                    </div>
                    <div class="col-lg-6">
                        <?= $form->field($model, 'address')->textarea(['maxlength' => true,'placeholder'=>'Petitt Barracks ZONE IV Zamboanga City.','rows' => 5]) ?>
                    </div>

                </div>
            </div>


            <div class="clear"></div>


            <div class="row" style="height: auto;background:ghostwhite;box-shadow: 0px 0px 0px 0px; padding: 30px;">

                <div class="row">
                    <div class="col-lg-12">
   
                   

                </div>

                <div class="col-lg-2">
                    <?= $form->field($model, 'resp_center')->textInput(['maxlength' => true,'placeholder' => 'Responsibility Center '])->label('') ?>
                </div>
                <div class="col-lg-4">
                <?php //$form->field($model, 'particulars')->textarea(['rows' => 5,'placeholder'=>'Particulars'])->label('') ?>
                <?php  //echo CKEditor::widget([ 'name' => "particulars", 'id' => 'particulars', 'preset' => 'full', 'value' => "", 'clientOptions' => ['height' => 200, 'width' => '100%'], ]);
                ?>
       <?= $form->field($model, 'particulars')->textarea(['rows' => 5,'placeholder'=>'Particulars'])->label('') ?>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'ppa')->textInput(['maxlength' => true])->label('MFO/PAP') ?>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'account_code')->textInput(['maxlength' => true,'placeholder'=>'5010101001'])->label('UACS Object Code.')?>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'amount')->textInput(['maxlength' => true,'placeholder'=>'0.00']) ?>
                </div>
            </div>


            <div class="row" style="height: auto; padding: 25px;">

                <div class="col-lg-6">
                    <h5>A. Certified</h5>
                    <h6>Charges to appropriation/allotment necessary, lawful and under my direct supervision</h6>
                    <h6>Supporting documents valid,proper and legal</h6>
                    <?= $form->field($model, 'requested_by')->widget(Select2::classname(), [
                        'data' => $listEmployee,
                        'id'=> 'cboEmployeeA',
                        'name'=> 'cboEmployeeA',
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select Employee','tabindex'=>-1],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'dropdownParent'=> new yii\web\JsExpression('$("#modalObligation")')
                        ],

                    ])->label(''); 
                    ?>

                </div>

                <div class="col-lg-6">
                    <h5>B. Certified</h5>
                    <h6>Allotment available and obligated for the purpose as indicated above</h6>
                    <div style="height: 24px;"></div>
                    <?= $form->field($model, 'funds_available')->widget(Select2::classname(), [
                        'data' => $listEmployee,
                        'id'=> 'cboEmployeeB',
                        'name'=> 'cboEmployeeB',
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select Employee'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'dropdownParent'=> new yii\web\JsExpression('$("#modalObligation")')
                        ],
                    ])->label(''); ?>
                </div>
            </div>

            <div class="space-20"></div>
            <div class="row" style="text-align: right;">
                <div class="col-lg-12">
                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['id'=>$model->isNewRecord ? "btnSave" : "btnUpdate",'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','name'=> $model->isNewRecord ? "btnSave" : "btnUpdate"]) ?>
                        <?= Html::submitButton($model->isNewRecord ? 'Save & Print' : 'Update & Print', ['id'=>$model->isNewRecord ? "btnSavePrint" : "btnUpdatePrint",'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','name'=> $model->isNewRecord ? "btnSavePrint" : "btnUpdatePrint"]) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>


           <!-- Panel End -->

        </div>
    </div>

</div>



<script type="text/javascript">
    document.getElementById('startButton2').onclick = function() {
        introJs().setOption('doneLabel', 'Next page').start().oncomplete(function() {
            window.location.href = 'index?multipage=true';
        });
    };
</script>