<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\apiservice\Notification */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="notification-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'hash')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sender')->textInput() ?>

    <?= $form->field($model, 'recipient')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'via')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'module')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'action')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
