<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\widgets\DepDrop;

use common\models\procurement\Expenditureobjecttype;
use common\models\procurement\Expenditureobjectsubtype;


/* @var $this yii\web\View */
/* @var $model common\models\finance\Accounttransaction */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="accounttransaction-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'object_type_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Expenditureobjecttype::find()->andWhere(['expenditure_object_id'=>$model->expenditure_object_id])->all(),'expenditure_object_type_id','name'),
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Object Type', 'id'=>'fa-object_type_id'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    'pluginEvents'=>[
                        "change" => 'function() { 
                            var objectTypeId=this.value;
                            $.post("/finance/osallotment/updateuacsforobjecttype/", 
                                {
                                    objectTypeId: objectTypeId
                                }, 
                                function(response){
                                    if(response){
                                       $("#osallotment-uacs_code").val(response.object_code);
                                    }
                                }
                            );
                        }
                    ',]
                ])->label('Object Type'); ?>
                
    <?= $form->field($model, 'object_sub_type_id')->widget(DepDrop::classname(), [
                    'type'=>DepDrop::TYPE_SELECT2,
                    'options'=>['id'=>'object_sub_type_id'],
                    'pluginOptions'=>[
                        'depends'=>['fa-object_type_id'],
                        'placeholder'=>'Select Object SubType',
                        'url'=>Url::to(['osallotment/listobjects'])
                    ],
                    'pluginEvents'=>[
                        "change" => 'function() { 
                            var objectSubTypeId=this.value;
                            $.post("/finance/osallotment/updateuacsforobjectsubtype/", 
                                {
                                    objectSubTypeId: objectSubTypeId
                                }, 
                                function(response){
                                    if(response){
                                       $("#osallotment-uacs_code").val(response.object_code);
                                    }
                                }
                            );
                        }
                    ',]
                ]); ?>
                
    <?= $form->field($model, 'uacs_code')->textInput()->label('UACS Code') ?>
                
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Apply', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
