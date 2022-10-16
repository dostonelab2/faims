<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\budget\Allocationadjustment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="allocationadjustment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'item_id')->textInput() ?>

    <?= $form->field($model, 'item_detail_id')->textInput() ?>

    <?= $form->field($model, 'source_item')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'create_date')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
