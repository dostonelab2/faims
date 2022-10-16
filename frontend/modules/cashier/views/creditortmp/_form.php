<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

use common\models\cashier\Creditortype;
/* @var $this yii\web\View */
/* @var $model common\models\cashier\Creditortmp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="creditortmp-form">

    <?php $form = ActiveForm::begin(['id' => 'new-creditor-form']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'creditor_type_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Creditortype::find()->all(),'creditor_type_id','name'),
                    'language' => 'en',
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])->label('Creditor Type'); ?> 
                
    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tin_number')->textInput(['maxlength' => true]) ?>

    <div class="alert alert-warning">
        <strong>Information!</strong><br/><br/> New Payees and Creditors are subject to review by the Procurement Staff. Kindly contact <b>Ms. Marilyn Ann C. Bueno</b> to facilitate this request.
    </div>
    <div class="form-group">
        <?= Html::submitButton('SUBMIT', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
