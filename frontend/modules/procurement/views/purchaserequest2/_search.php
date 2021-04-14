<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\PurchaserequestSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="purchaserequest-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php // $form->field($model, 'purchase_request_id') ?>

    <?= $form->field($model, 'purchase_request_number')->label(false) ?>

    <?php // $form->field($model, 'purchase_request_sai_number') ?>

    <?php // $form->field($model, 'division_id') ?>

    <?php //$form->field($model, 'section_id') ?>

    <?php // echo $form->field($model, 'purchase_request_date') ?>

    <?php // echo $form->field($model, 'purchase_request_saidate') ?>

    <?php // echo $form->field($model, 'purchase_request_purpose') ?>

    <?php // echo $form->field($model, 'purchase_request_referrence_no') ?>

    <?php // echo $form->field($model, 'purchase_request_project_name') ?>

    <?php // echo $form->field($model, 'purchase_request_location_project') ?>

    <?php // echo $form->field($model, 'purchase_request_requestedby_id') ?>

    <?php // echo $form->field($model, 'purchase_request_approvedby_id') ?>

    <?php // echo $form->field($model, 'user_id') ?>
    <!--
    <div class="form-group">
        <?php // Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?php //Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>
    -->

    <?php ActiveForm::end(); ?>

</div>
