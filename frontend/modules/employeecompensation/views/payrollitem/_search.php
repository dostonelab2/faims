<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\employeecompensation\PayrollitemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payrollitem-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'payroll_item_id') ?>

    <?= $form->field($model, 'payroll_id') ?>

    <?= $form->field($model, 'creditor_id') ?>

    <?= $form->field($model, 'salary') ?>

    <?= $form->field($model, 'gross_amount_earned') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
