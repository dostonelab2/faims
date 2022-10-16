<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

use common\models\finance\Taxcategory;

$taxcat = Taxcategory::find()->where([
                                    'rate1' => $model->rate1, 'rate2' => $model->rate2, 
                                        ])->one();
if($taxcat)
    $model->tax_category_id = $taxcat->tax_category_id;
/* @var $this yii\web\View */
/* @var $model common\models\finance\Accounttransaction */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="accounttransaction-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'tax_registered')->widget(Select2::classname(), [
                    'data' => ['0'=>'NO', '1'=>'YES'],
                    'language' => 'en',
                    //'options' => ['placeholder' => 'Select Payee','readonly'=>'readonly'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label('Tax Registered'); ?>
                
    <?= $form->field($model, 'tax_category_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Taxcategory::find()->all(),'tax_category_id','name'),
                    'language' => 'en',
                    //'options' => ['placeholder' => 'Select Payee','readonly'=>'readonly'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label('Tax Category'); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Apply', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
