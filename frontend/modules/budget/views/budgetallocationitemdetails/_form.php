<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\budget\Budgetallocationitemdetails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="budgetallocationitemdetails-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'budget_allocation_item_id')->textInput() ?>

    <?= $form->field($model, 'fund_source_id')->textInput() ?>

    <?= $form->field($model, 'program_id')->textInput() ?>

    <?= $form->field($model, 'section_id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'active')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
