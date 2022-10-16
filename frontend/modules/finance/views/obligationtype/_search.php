<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\finance\ObligationtypeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="obligationtype-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'type_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'project_id') ?>

    <?= $form->field($model, 'fund_category_id') ?>

    <?= $form->field($model, 'active') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
