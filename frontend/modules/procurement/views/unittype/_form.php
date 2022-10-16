<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Unittype */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="unittype-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name_short')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_long')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
