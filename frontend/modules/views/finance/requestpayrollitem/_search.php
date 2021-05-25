<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\finance\RequestpayrollitemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="requestpayrollitem-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'request_payroll_item_id') ?>

    <?= $form->field($model, 'request_payroll_id') ?>

    <?= $form->field($model, 'request_id') ?>

    <?= $form->field($model, 'osdv_id') ?>

    <?= $form->field($model, 'dv_id') ?>

    <?php // echo $form->field($model, 'creditor_id') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'particulars') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'tax') ?>

    <?php // echo $form->field($model, 'status_id') ?>

    <?php // echo $form->field($model, 'osdv_attributes') ?>

    <?php // echo $form->field($model, 'active') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
