<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\Ppmpitem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ppmpitem-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ppmp_id')->textInput() ?>

    <?= $form->field($model, 'item_id')->textInput() ?>

    <?= $form->field($model, 'item_category_id')->textInput() ?>

    <?= $form->field($model, 'ppmp_item_category_id')->textInput() ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'item_specification')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <?= $form->field($model, 'unit')->textInput() ?>

    <?= $form->field($model, 'cost')->textInput() ?>

    <?= $form->field($model, 'estimated_budget')->textInput() ?>

    <?= $form->field($model, 'mode_of_procurement')->textInput() ?>

    <?= $form->field($model, 'availability')->textInput() ?>

    <?= $form->field($model, 'q1')->textInput() ?>

    <?= $form->field($model, 'q2')->textInput() ?>

    <?= $form->field($model, 'q3')->textInput() ?>

    <?= $form->field($model, 'q4')->textInput() ?>

    <?= $form->field($model, 'q5')->textInput() ?>

    <?= $form->field($model, 'q6')->textInput() ?>

    <?= $form->field($model, 'q7')->textInput() ?>

    <?= $form->field($model, 'q8')->textInput() ?>

    <?= $form->field($model, 'q9')->textInput() ?>

    <?= $form->field($model, 'q10')->textInput() ?>

    <?= $form->field($model, 'q11')->textInput() ?>

    <?= $form->field($model, 'q12')->textInput() ?>

    <?= $form->field($model, 'active')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
