<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Requestpayrollitem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="requestpayrollitem-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'request_payroll_id')->textInput() ?>

    <?= $form->field($model, 'request_id')->textInput() ?>

    <?= $form->field($model, 'osdv_id')->textInput() ?>

    <?= $form->field($model, 'dv_id')->textInput() ?>

    <?= $form->field($model, 'creditor_id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'particulars')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'tax')->textInput() ?>

    <?= $form->field($model, 'status_id')->textInput() ?>

    <?= $form->field($model, 'osdv_attributes')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'active')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
