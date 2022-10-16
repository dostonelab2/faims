<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\cashier\Lddapadaitem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lddapadaitem-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'lddapada_id')->textInput() ?>

    <?= $form->field($model, 'creditor_id')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gross_amount')->textInput() ?>

    <?= $form->field($model, 'alobs_id')->textInput() ?>

    <?= $form->field($model, 'expenditure_object_id')->textInput() ?>

    <?= $form->field($model, 'check_number')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
