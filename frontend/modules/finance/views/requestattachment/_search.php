<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\finance\RequestattachmentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="requestattachment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'request_attachment_id') ?>

    <?= $form->field($model, 'request_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'attachment_type_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
