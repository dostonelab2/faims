<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\employeecompensation\Payrollitem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payrollitem-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'payroll_id')->textInput() ?>

    <?= $form->field($model, 'creditor_id')->textInput() ?>

    <?= $form->field($model, 'salary')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gross_amount_earned')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
