<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\DisbursementSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="disbursement-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'dv_id') ?>

    <?= $form->field($model, 'dv_no') ?>

    <?= $form->field($model, 'dv_date') ?>

    <?= $form->field($model, 'particulars') ?>

    <?= $form->field($model, 'payee') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'dv_amount') ?>

    <?php // echo $form->field($model, 'dv_total') ?>

    <?php // echo $form->field($model, 'certified_a') ?>

    <?php // echo $form->field($model, 'certified_b') ?>

    <?php // echo $form->field($model, 'approved') ?>

    <?php // echo $form->field($model, 'os_no') ?>

    <?php // echo $form->field($model, 'taxable') ?>

    <?php // echo $form->field($model, 'dv_type') ?>

    <?php // echo $form->field($model, 'po_no') ?>

    <?php // echo $form->field($model, 'funding_id') ?>

    <?php // echo $form->field($model, 'fundings') ?>

    <?php // echo $form->field($model, 'supporting_docs') ?>

    <?php // echo $form->field($model, 'cash_available') ?>

    <?php // echo $form->field($model, 'subject_ada') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
