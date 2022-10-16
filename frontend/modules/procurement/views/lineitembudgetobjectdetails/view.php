<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Lineitembudgetobjectdetails */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Lineitembudgetobjectdetails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lineitembudgetobjectdetails-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->line_item_budget_object_details_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->line_item_budget_object_details_id], [
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
            'line_item_budget_object_details_id',
            'line_item_budget_object_id',
            'object_detail_id',
            'quantity',
            'name',
            'details:ntext',
            'amount',
        ],
    ]) ?>

</div>
