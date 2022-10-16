<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\apiservice\NotificationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="notification-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'notification_id') ?>

    <?= $form->field($model, 'hash') ?>

    <?= $form->field($model, 'sender') ?>

    <?= $form->field($model, 'recipient') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'message') ?>

    <?php // echo $form->field($model, 'via') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'module') ?>

    <?php // echo $form->field($model, 'action') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
