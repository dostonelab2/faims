<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\budget\Budgetallocationitem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Budgetallocationitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budgetallocationitem-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->budget_allocation_item_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->budget_allocation_item_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'budget_allocation_item_id',
            'budget_allocation_id',
            'name',
            'code',
            'category_id',
            'amount',
        ],
    ]) ?>

</div>
