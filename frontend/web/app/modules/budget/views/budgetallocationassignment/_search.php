<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\budget\BudgetallocationassignmentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="budgetallocationassignment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'budget_allocation_assignment_id') ?>

    <?= $form->field($model, 'budget_allocation_id') ?>

    <?= $form->field($model, 'request_id') ?>

    <?= $form->field($model, 'budget_allocation_item_id') ?>

    <?= $form->field($model, 'budget_allocation_item_detail_id') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
