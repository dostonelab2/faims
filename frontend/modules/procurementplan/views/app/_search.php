<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\AppSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ppmpitem-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ppmp_item_id') ?>

    <?= $form->field($model, 'ppmp_id') ?>

    <?= $form->field($model, 'item_id') ?>

    <?= $form->field($model, 'item_category_id') ?>

    <?= $form->field($model, 'ppmp_item_category_id') ?>

    <?php // echo $form->field($model, 'code') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'item_specification') ?>

    <?php // echo $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'unit') ?>

    <?php // echo $form->field($model, 'cost') ?>

    <?php // echo $form->field($model, 'estimated_budget') ?>

    <?php // echo $form->field($model, 'mode_of_procurement') ?>

    <?php // echo $form->field($model, 'availability') ?>

    <?php // echo $form->field($model, 'q1') ?>

    <?php // echo $form->field($model, 'q2') ?>

    <?php // echo $form->field($model, 'q3') ?>

    <?php // echo $form->field($model, 'q4') ?>

    <?php // echo $form->field($model, 'q5') ?>

    <?php // echo $form->field($model, 'q6') ?>

    <?php // echo $form->field($model, 'q7') ?>

    <?php // echo $form->field($model, 'q8') ?>

    <?php // echo $form->field($model, 'q9') ?>

    <?php // echo $form->field($model, 'q10') ?>

    <?php // echo $form->field($model, 'q11') ?>

    <?php // echo $form->field($model, 'q12') ?>

    <?php // echo $form->field($model, 'month') ?>

    <?php // echo $form->field($model, 'active') ?>

    <?php // echo $form->field($model, 'status_id') ?>

    <?php // echo $form->field($model, 'supplemental') ?>

    <?php // echo $form->field($model, 'create_user') ?>

    <?php // echo $form->field($model, 'create_date') ?>

    <?php // echo $form->field($model, 'update_user') ?>

    <?php // echo $form->field($model, 'update_date') ?>

    <?php // echo $form->field($model, 'submitted_user') ?>

    <?php // echo $form->field($model, 'submitted_date') ?>

    <?php // echo $form->field($model, 'approved_user') ?>

    <?php // echo $form->field($model, 'approved_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
