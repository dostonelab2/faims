<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\finance\ReportsignatorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reportsignatory-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'report_signatory_id') ?>

    <?= $form->field($model, 'division_id') ?>

    <?= $form->field($model, 'scope') ?>

    <?= $form->field($model, 'box') ?>

    <?= $form->field($model, 'user1') ?>

    <?php // echo $form->field($model, 'user2') ?>

    <?php // echo $form->field($model, 'user3') ?>

    <?php // echo $form->field($model, 'active_user') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
