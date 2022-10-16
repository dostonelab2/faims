<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\ObligationrequestSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="obligationrequest-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'obligation_request_id') ?>

    <?= $form->field($model, 'os_no') ?>

    <?= $form->field($model, 'os_date') ?>

    <?= $form->field($model, 'particulars') ?>

    <?= $form->field($model, 'ppa') ?>

    <?php // echo $form->field($model, 'account_code') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'payee') ?>

    <?php // echo $form->field($model, 'office') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'requested_by') ?>

    <?php // echo $form->field($model, 'requested_bypos') ?>

    <?php // echo $form->field($model, 'funds_available') ?>

    <?php // echo $form->field($model, 'funds_available_pos') ?>

    <?php // echo $form->field($model, 'purchase_no') ?>

    <?php // echo $form->field($model, 'os_type') ?>

    <?php // echo $form->field($model, 'dv_no') ?>

    <?php // echo $form->field($model, 'username') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
