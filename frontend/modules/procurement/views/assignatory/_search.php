<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\AssignatorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="assignatory-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'assignatory_id') ?>

    <?= $form->field($model, 'CompanyTitle') ?>

    <?= $form->field($model, 'RegionOffice') ?>

    <?= $form->field($model, 'Address') ?>

    <?= $form->field($model, 'report_title') ?>

    <?php // echo $form->field($model, 'assignatory_1') ?>

    <?php // echo $form->field($model, 'assignatory_2') ?>

    <?php // echo $form->field($model, 'assignatory_3') ?>

    <?php // echo $form->field($model, 'assignatory_4') ?>

    <?php // echo $form->field($model, 'assignatory_5') ?>

    <?php // echo $form->field($model, 'assignatory_6') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
