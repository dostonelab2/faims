<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\budget\Budgetallocation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="budgetallocation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'section_id')->textInput() ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
