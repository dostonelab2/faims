<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\cashier\LddapadaitemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lddapadaitem-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'lddapada_item_id') ?>

    <?= $form->field($model, 'lddapada_id') ?>

    <?= $form->field($model, 'creditor_id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'bank_name') ?>

    <?php // echo $form->field($model, 'account_number') ?>

    <?php // echo $form->field($model, 'gross_amount') ?>

    <?php // echo $form->field($model, 'alobs_id') ?>

    <?php // echo $form->field($model, 'expenditure_object_id') ?>

    <?php // echo $form->field($model, 'check_number') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
