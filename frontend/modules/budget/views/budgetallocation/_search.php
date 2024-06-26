<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\budget\BudgetallocationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="budgetallocation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'budget_allocation_id') ?>

    <?= $form->field($model, 'section_id') ?>

    <?= $form->field($model, 'year') ?>

    <?= $form->field($model, 'amount') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
