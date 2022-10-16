<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\budget\ObligationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="obligation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'obligation_id') ?>

    <?= $form->field($model, 'financial_request_id') ?>

    <?= $form->field($model, 'obligation_number') ?>

    <?= $form->field($model, 'obligation_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
