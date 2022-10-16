<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\budget\Allocationadjustment */

$this->title = 'Update Allocationadjustment: ' . $model->allocation_adjustment_id;
$this->params['breadcrumbs'][] = ['label' => 'Allocationadjustments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->allocation_adjustment_id, 'url' => ['view', 'id' => $model->allocation_adjustment_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="allocationadjustment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
