<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Expenditure */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="expenditure-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'expenditure_class_id')->textInput() ?>

    <?= $form->field($model, 'expenditure_subclass_id')->textInput() ?>

    <?= $form->field($model, 'expenditure_object_id')->textInput() ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
