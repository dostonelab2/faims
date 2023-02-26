<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Reportsignatory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reportsignatory-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'division_id')->textInput() ?>

    <?= $form->field($model, 'scope')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'box')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user1')->textInput() ?>

    <?= $form->field($model, 'user2')->textInput() ?>

    <?= $form->field($model, 'user3')->textInput() ?>

    <?= $form->field($model, 'active_user')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
