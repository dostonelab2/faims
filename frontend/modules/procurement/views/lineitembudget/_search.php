<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\LineitembudgetSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lineitembudget-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'period') ?>

    <?= $form->field($model, 'duration_start') ?>

    <?php // echo $form->field($model, 'duration_end') ?>

    <?php // echo $form->field($model, 'division_id') ?>

    <?php // echo $form->field($model, 'unit_id') ?>

    <?php // echo $form->field($model, 'project_id') ?>

    <?php // echo $form->field($model, 'program_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
