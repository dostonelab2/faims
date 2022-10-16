<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\budget\BudgetallocationitemdetailsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="budgetallocationitemdetails-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'budget_allocation_item_detail_id') ?>

    <?= $form->field($model, 'budget_allocation_item_id') ?>

    <?= $form->field($model, 'fund_source_id') ?>

    <?= $form->field($model, 'program_id') ?>

    <?= $form->field($model, 'section_id') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'active') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
