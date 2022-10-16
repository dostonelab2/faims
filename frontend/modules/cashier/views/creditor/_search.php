<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\cashier\CreditorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="creditor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'creditor_id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'bank_name') ?>

    <?= $form->field($model, 'account_number') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
