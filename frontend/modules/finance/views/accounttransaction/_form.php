<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Accounttransaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="accounttransaction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'request_id')->textInput() ?>

    <?= $form->field($model, 'account_id')->textInput() ?>

    <?= $form->field($model, 'transaction_type')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
