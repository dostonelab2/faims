<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\finance\AccountSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'account_id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'object_code') ?>

    <?= $form->field($model, 'account_code') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
