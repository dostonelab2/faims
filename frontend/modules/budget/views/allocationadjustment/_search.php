<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\budget\AllocationadjustmentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="allocationadjustment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'allocation_adjustment_id') ?>

    <?= $form->field($model, 'item_id') ?>

    <?= $form->field($model, 'item_detail_id') ?>

    <?= $form->field($model, 'source_item') ?>

    <?= $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'create_date') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
