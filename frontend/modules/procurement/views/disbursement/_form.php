<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\widgets\SwitchInput;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Disbursement */
/* @var $form yii\widgets\ActiveForm */


$BaseURL = $GLOBALS['frontend_base_uri'];
$this->registerJsFile('https://code.jquery.com/ui/1.12.1/jquery-ui.js');
$this->registerJsFile($BaseURL.'js/jquery.tabletojson.js');
$this->registerJsFile($BaseURL.'js/disbursement/function.js');

if ($model->certified_a=='') {
    $model->certified_a =  $assig->assignatory_1;
}
if ($model->certified_b=='') {
    $model->certified_b =  $assig->assignatory_2;
}

if ($model->approved=='') {
    $model->approved =  $assig->assignatory_3;
}

?>
<div class="disbursement-form">


    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <div class="col-lg-12" style="padding: 25px;">
            <?=
            $form->field($model, 'dv_type')->widget(Select2::classname(), [
                'name' => 'cbodvType',
                'id'=> 'cbodvType',
                'hideSearch' => true,
                'disabled'=> $model->isNewRecord ? false : false,
                'data' => $dvtype_data,
                'options' => [
                    'multiple' => false
                ],
                'pluginEvents' => [
                    "change" => "function() {
                                             var data=$(this).val();
                                             
                                             if (data=='MDS') {
                                                    $('#sono').show(500);
                                                    $('#specify').hide(500);
                                                    $('#pono').hide(500);
                                                    $('#taxable').show(500);
                                                    $(\".cboEmployeeA\").select2({
                                                        disabled : false,
                                                        dropdownParent: $('#modalDisbursement'),
                                                        theme : \"krajee\"
                                                    });
                                                      //$(\"#disbursement-certified_a\").val('').trigger('change');
                   
                                             }
                                             if (data=='TF') {
                                                    $('#sono').hide(500);
                                                    $('#specify').show(500);
                                                    $('#pono').show(500);
                                                    var value=0;
                                                    $(\"input[name=rdList][value=\" + value + \"]\").prop('checked', true);
                                                    $('#taxable').show(500);
                                                    $(\".cboEmployeeA\").select2({
                                                        disabled : false,
                                                        dropdownParent: $('#modalDisbursement'),
                                                        theme : \"krajee\",
                                                    });
                                                  
                                             }
                                             if (data == 'ST') {
                                                    $('#sono').hide(500);
                                                    $('#specify').show(500);
                                                    $('#pono').show(500);
                                                    $('#taxable').show(500);
                                                    $(\".cboEmployeeA\").select2({
                                                        disabled : false,
                                                        dropdownParent: $('#modalDisbursement'),
                                                        theme : \"krajee\",
                                                    });
                                             }
                                             if (data == 'BI') {
                                                    $('#sono').hide(500);
                                                    $('#specify').hide(500);
                                                    $('#pono').hide(500);
                                                    $('#taxable').hide(500);
                                                    $(\".cboEmployeeA\").select2({
                                                        disabled : false,
                                                        dropdownParent: $('#modalDisbursement'),
                                                        theme : \"krajee\",
                                                    });
                                             }
                                    },",

                ],
            ])->label('OS Type');
            ?>
        </div>
    </div>

    <div class="space-20"></div>

    <div class="row">

        <div class="col-lg-12">

            <div class="col-lg-1" id="taxable">
                <?php $model->isNewRecord==1 ? $model->taxable=0:$model->taxable;?>
                <?= $form->field($model, 'taxable')->radioList(array('1'=>'Yes','0'=>'No'),['itemOptions' => []]); ?>
            </div>

            <div class="col-lg-2" id="sono">

                <?= $form->field($model, 'os_no')->widget(Select2::classname(), [
                    'data' => $listSono,
                    'id'=> 'cboSono',
                    'name'=> 'cboSono',
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select O.R.S','style'=> 'padding-top:0px;','disabled'=> false,
                        'class'=> 'cboSono','tabindex'=>-1],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'dropdownParent'=> new yii\web\JsExpression('$("#modalDisbursement")')
                    ],
                    'pluginEvents' => [
                        "change" => "function() {
                                                 var so_num=$(this).val();
                                                jQuery.ajax( {
                                                    type: \"POST\",
                                                    data: {
                                                        so_num: so_num,
                                                    },
                                                    url: \"/procurement/disbursement/checkimportid\",
                                                    dataType: \"text\",
                                                    success: function ( response ) {                                 
                                                        data = $.parseJSON(response);
                                                        $.each(data, function(i, item) {
                                                            var particular = item.particulars;
                                                            var address = item.address;
                                                            var amount = item.amount;
                                                            var payee = item.payee;
                                                            var osdate = item.os_date;
                                                            //CKEDITOR.instances[ $('#disbursement-particulars') ].insertHTML(particular);
                                                            $('#disbursement-particulars').text(particular);
                                                            $('#disbursement-address').text(address);
                                                            $('#disbursement-dv_amount').val(amount);
                                                            $('#disbursement-payee').val(payee);
                                                            document.getElementById('disbursement-dv_date').valueAsDate = new Date(osdate);
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

        <div class="col-lg-12">

        <div class="col-lg-3">
            <?= $form->field($model, 'payee')->textInput(['maxlength' => true,'placeholder'=>'John Doe']) ?>
            <?= $form->field($model, 'fundings')->textInput(['maxlength' => true,'placeholder'=>'Fundings']) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'dv_no') ->textInput(['placeholder'=>'<AutoGenerated>','readonly'=>'true','tabindex'=>'-1'])->label('OS # :')?>
            <?= $form->field($model, 'dv_date')->input("date",['value' =>  date("Y-m-d")])->label('OS Date. ')?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'address')->textarea(['maxlength' => true,'placeholder'=>'Petitt Barracks ZONE IV Zamboanga City.','rows' => 5]) ?>
        </div>

        </div>

    </div>

    <div class="clear"></div>

    <div class="col-lg-12">

    <div class="row" style="height: auto;background:ghostwhite;box-shadow: 0px 0px 0px 0.2px; padding: 25px;padding-left:0px;padding-right:0px;">
        <div class="col-lg-9">
            <?=$form->field($model, 'particulars')->textarea(['rows' => 5,'placeholder'=>'Particulars'])->label('Particulars') ?>
            <?php /*$form->field($model, 'particulars')->widget(CKEditor::className(), [
                'options' => ['rows' => 5],
                'preset' => 'basic',

            ])*/ ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'dv_amount')->textInput(['maxlength' => true,'placeholder'=>'0.00'])->label('Amount') ?>
        </div>
    </div>

    </div>

    <div class="clear"></div>

    <div class="row">
        <div class="col-lg-12">
            <div class="col-lg-4">
                <div style="height: 20px;"></div>
                <label>Certified Expenses/ Advances necessary, lawful and under my direct supervision</label>
            </div>
            <div class="col-lg-4">
                <div style="height: 20px;"></div>
                <?= $form->field($model, 'supporting_docs')->checkbox(array(
                    'label'=>'Supporting documents complete and proper',
                    'labelOptions'=>array('style'=>''),
                    'disabled'=>false,
                ))->label(''); ?>
                <?= $form->field($model, 'cash_available')->checkbox(array(
                    'label'=>'Cash Available',
                    'labelOptions'=>array('style'=>''),
                    'disabled'=>false,
                ))->label(''); ?>
                <?= $form->field($model, 'subject_ada')->checkbox(array(
                    'label'=>'Subject to ADA (where applicable)',
                    'labelOptions'=>array('style'=>''),
                    'disabled'=>false,
                ))->label(''); ?>
            </div>
            <div class="col-lg-4">
                <div style="height: 20px;"></div>
                <label>Approved for payment</label>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-12">

        <div class="col-lg-4">
            <?= $form->field($model, 'certified_a')->widget(Select2::classname(), [
                'data' => $listEmployee,
                'id'=> 'cboEmployeeA',
                'name'=> 'cboEmployeeA',
                'language' => 'en',
                'options' => ['placeholder' => 'Select Employee','tabindex'=>-1,'class'=>'cboEmployeeA'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'dropdownParent'=> new yii\web\JsExpression('$("#modalDisbursement")')
                ],

            ])->label(''); ?>
        </div>

        <div class="col-lg-4">
            <?= $form->field($model, 'certified_b')->widget(Select2::classname(), [
                'data' => $listEmployee,
                'id'=> 'cboEmployeeB',
                'name'=> 'cboEmployeeB',
                'language' => 'en',
                'options' => ['placeholder' => 'Select Employee','tabindex'=>-1],
                'pluginOptions' => [
                    'allowClear' => true,
                    'dropdownParent'=> new yii\web\JsExpression('$("#modalDisbursement")')
                ],

            ])->label(''); ?>
        </div>

        <div class="col-lg-4">
            <?= $form->field($model, 'approved')->widget(Select2::classname(), [
                'data' => $listEmployee,
                'id'=> 'cboEmployeeC',
                'name'=> 'cboEmployeeC',
                'language' => 'en',
                'options' => ['placeholder' => 'Select Employee','tabindex'=>-1],
                'pluginOptions' => [
                    'allowClear' => true,
                    'dropdownParent'=> new yii\web\JsExpression('$("#modalDisbursement")')
                ],
            ])->label(''); ?>
        </div>

        </div>

    </div>

    <div class="row">

        <div class="col-lg-12">

            <div class="col-lg-4" id="specify">
                <label>Pls Specify...</label>
                    <?= Html::radioList('rdList',1,array('0'=>'Purchase','1'=>'Salary','2'=>'Scholarship','3'=>'Travel/Others'),['itemOptions' => ['disabled' => $model->isNewRecord ? false : true]]); ?>
            </div>

            <div class="col-lg-4" id="pono">

                <?= $form->field($model, 'po_no')->widget(Select2::classname(), [
                    'data' => $listPono,
                    'id'=> 'cboPono',
                    'name'=> 'cboPono',
                    'theme'=>Select2::THEME_KRAJEE,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select P.O.','style'=> 'padding-top:0px;','disabled'=>true,
                        'class'=> 'cboPono'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'dropdownParent'=> new yii\web\JsExpression('$("#modalDisbursement")')
                    ],
                    'pluginEvents' => [
                        "change" => "function() {
                                                 var dv_num=$(this).val();
                                                 jQuery.ajax( {
                                                    type: \"POST\",
                                                    data: {
                                                        dv_num: dv_num,
                                                    },
                                                    url: \"/procurement/disbursement/checkimportid2\",
                                                    dataType: \"text\",
                                                    success: function ( response ) {                                 
                                                        data = $.parseJSON(response);
                                                        $.each(data, function(i, item) {
                                                            var particular = item.Particulars;
                                                            var amount = item.Amount;
                                                            var address = item.supplier_address;
                                                            var payee = item.supplier_name;
                                                            $('#disbursement-particulars').text(particular);
                                                            $('#disbursement-dv_amount').val(amount);
                                                            $('#disbursement-address').text(address);
                                                            $('#disbursement-payee').val(payee);
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

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>