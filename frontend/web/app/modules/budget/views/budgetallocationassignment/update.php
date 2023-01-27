<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\budget\Budgetallocationassignment */

$this->title = 'Update Budgetallocationassignment: ' . $model->budget_allocation_assignment_id;
$this->params['breadcrumbs'][] = ['label' => 'Budgetallocationassignments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->budget_allocation_assignment_id, 'url' => ['view', 'id' => $model->budget_allocation_assignment_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="budgetallocationassignment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
