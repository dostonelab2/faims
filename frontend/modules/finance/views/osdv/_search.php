<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\finance\OsdvSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="osdv-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'osdv_id') ?>

    <?= $form->field($model, 'request_id') ?>

    <?= $form->field($model, 'type_id') ?>

    <?= $form->field($model, 'expenditure_class_id') ?>

    <?= $form->field($model, 'status_id') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
