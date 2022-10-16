<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\Item */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'item_category_id')->textInput() ?>

    <?= $form->field($model, 'item_code')->textInput() ?>

    <?= $form->field($model, 'item_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'unit_of_measure_id')->textInput() ?>

    <?= $form->field($model, 'price_catalogue')->textInput() ?>

    <?= $form->field($model, 'availability')->textInput() ?>

    <?= $form->field($model, 'last_update')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
