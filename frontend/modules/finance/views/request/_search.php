<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\finance\RequestSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'request_id') ?>

    <?= $form->field($model, 'request_number') ?>

    <?= $form->field($model, 'request_date') ?>

    <?= $form->field($model, 'request_type_id') ?>

    <?= $form->field($model, 'payee') ?>

    <?php // echo $form->field($model, 'particulars') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'status_id') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
