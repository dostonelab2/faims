<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\cashier\LddapadaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lddapada-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'lddapada_id') ?>

    <?= $form->field($model, 'batch_number') ?>

    <?= $form->field($model, 'batch_date') ?>

    <?= $form->field($model, 'certified_correct_id') ?>

    <?= $form->field($model, 'approved_id') ?>

    <?php // echo $form->field($model, 'validated1_id') ?>

    <?php // echo $form->field($model, 'validated2_id') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
