<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\finance\OsallotmentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="osallotment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'os_allotment_id') ?>

    <?= $form->field($model, 'osdv_id') ?>

    <?= $form->field($model, 'expenditure_class_id') ?>

    <?= $form->field($model, 'expenditure_object_id') ?>

    <?= $form->field($model, 'amount') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
