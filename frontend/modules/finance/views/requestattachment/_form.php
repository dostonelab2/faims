<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Requestattachment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="requestattachment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'request_id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'attachment_type_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
