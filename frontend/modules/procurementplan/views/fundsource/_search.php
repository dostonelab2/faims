<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\FundsourceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fundsource-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'fund_source_id') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'active') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
