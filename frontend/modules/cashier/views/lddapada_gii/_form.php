<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\cashier\Lddapada */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lddapada-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'batch_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'batch_date')->textInput() ?>

    <?= $form->field($model, 'certified_correct_id')->textInput() ?>

    <?= $form->field($model, 'approved_id')->textInput() ?>

    <?= $form->field($model, 'validated1_id')->textInput() ?>

    <?= $form->field($model, 'validated2_id')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
