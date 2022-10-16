<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\finance\AccounttransactionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="accounttransaction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'account_transaction_id') ?>

    <?= $form->field($model, 'request_id') ?>

    <?= $form->field($model, 'account_id') ?>

    <?= $form->field($model, 'transaction_type') ?>

    <?= $form->field($model, 'amount') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
