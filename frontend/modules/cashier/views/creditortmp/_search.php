<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\cashier\CreditortmpSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="creditortmp-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'creditor_id') ?>

    <?= $form->field($model, 'creditor_type_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'address') ?>

    <?= $form->field($model, 'bank_name') ?>

    <?php // echo $form->field($model, 'account_number') ?>

    <?php // echo $form->field($model, 'tin_number') ?>

    <?php // echo $form->field($model, 'requested_by') ?>

    <?php // echo $form->field($model, 'date_request') ?>

    <?php // echo $form->field($model, 'active') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
