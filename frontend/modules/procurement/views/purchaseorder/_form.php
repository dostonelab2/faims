<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\widgets\SwitchInput;
use common\models\procurement\UnitType;
use yii\helpers\ArrayHelper;

use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Obligationrequest */
/* @var $form yii\widgets\ActiveForm */

$BaseURL = $GLOBALS['frontend_base_uri'];
$units = UnitType::find()->all();
$listUnits = ArrayHelper::map($units,'unit_type_id','name_short');

$this->registerJsFile('https://code.jquery.com/ui/1.12.1/jquery-ui.js');
    
?>

<div class="purchaseorder-form">

    <div class="panel panel-default disabled" id="mPanel">
        <div class="panel-body">

            <?php   $form = ActiveForm::begin(['id' => 'purchaseorder-form', 'enableClientValidation' => true,'action' => 'update','method' => 'post']); ?>

            <div class="row">
                <div class="col-lg-6">
                    
                <?= $form->field($model, 'bids_item_description')->widget(CKEditor::className(), [
                    'options' => ['rows' => 6],
                    'preset' => 'full'
                ])->label('Item Description') ?>
            </div>
            <div class="col-lg-6">
                <div class="col-lg-12">
                <label>Unit</label>
                    <?=
                   /* Select2::widget([
                                            'name' => 'txtunits',
                                            'id'=> 'txtunits',
                                            'data' => $listUnits,
                                            'options' => ['placeholder' => 'Select Unit Type','tabindex'=>0,],
                                            'pluginEvents' => [
                                                "change" => "function() {
                                                                 var data=$(this).val();
                                                        }",
                                            ],
                                        ]);*/
// Normal select with ActiveForm & model
 $form->field($model, 'bids_unit')->widget(Select2::classname(), [
    'data' => $listUnits,
    'language' => 'en',
    'options' => ['placeholder' => 'Select Unit Type','tabindex'=>0,],
    'pluginEvents' => [
        "change" => "function() {
                         var data=$(this).val();
                }",
    ],
])->label('');
                                        
    
                    ?>
                </div>
                <div class="col-lg-12">
                     <?= $form->field($model, 'bids_quantity')->textInput(['maxlength' => true,'placeholder'=>'0'])->label('Quantity') ?> 
                </div>
                <div class="col-lg-12">
                     <?= $form->field($model, 'bids_price')->textInput(['maxlength' => true,'placeholder'=>'0'])->label('Price'); ?> 
                </div>   
                <div class="col-lg-12">
                     <?= $form->field($model2, 'place_of_delivery')->textInput(['maxlength' => true,'placeholder'=>'Place of Delivery','id'=>'txtdelivery','name'=> 'txtdelivery'])->label('Place of Delivery'); ?> 
                </div> 
                <div class="col-lg-12">
                <?= $form->field($model2, 'date_of_delivery')->input("date",['value' =>  date("Y-m-d"),'id'=>'txtdelivery_date','name'=> 'txtdelivery_date'])->label('Date of Delivery') ?>
                </div> 
                <div class="col-lg-12">
                     <?= $form->field($model2, 'delivery_term')->textInput(['maxlength' => true,'placeholder'=>'Terms of Delivery','id'=>'txtdelivery_term','name'=> 'txtdelivery_term'])->label('Delivery Term'); ?> 
                </div> 
                <div class="col-lg-12">
                     <?= $form->field($model2, 'payment_term')->textInput(['maxlength' => true,'placeholder'=>'Terms of Payment','id'=>'txtpayment_term','name'=> 'txtpayment_term'])->label('Payment Term'); ?> 
                </div> 
                <div class="col-lg-12">
                     <?= $form->field($model2, 'mode_of_procurement')->textInput(['maxlength' => true,'placeholder'=>'Mode of Procurement','id'=>'txtmode_of_procurement','name'=> 'txtmode_of_procurement'])->label('Mode of Procurement'); ?> 
                </div>    
                 
            </div>
        </div>
                
        
            <div class="space-20"></div>
            <div class="row" style="text-align: right;">  
                <div class="col-lg-12">
                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['id'=>$model->isNewRecord ? "btnSave" : "btnUpdate",'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','name'=> $model->isNewRecord ? "btnSave" : "btnUpdate"]) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>


           <!-- Panel End -->

        </div>
    </div>

</div>
