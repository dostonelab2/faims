<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\budget\Budgetallocation */

$this->title = 'Update Budgetallocation: ' . $model->budget_allocation_id;
$this->params['breadcrumbs'][] = ['label' => 'Budgetallocations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->budget_allocation_id, 'url' => ['view', 'id' => $model->budget_allocation_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="budgetallocation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
