<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\budget\Budgetallocationitemdetails */

$this->title = 'Update Budgetallocationitemdetails: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Budgetallocationitemdetails', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->budget_allocation_item_detail_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="budgetallocationitemdetails-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
