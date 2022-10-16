<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\Ppmpitem */

$this->title = $model->ppmp_item_id;
$this->params['breadcrumbs'][] = ['label' => 'Ppmpitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ppmpitem-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->ppmp_item_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->ppmp_item_id], [
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
            'ppmp_item_id',
            'ppmp_id',
            'item_id',
            'item_category_id',
            'ppmp_item_category_id',
            'code',
            'description:ntext',
            'item_specification:ntext',
            'quantity',
            'unit',
            'cost',
            'estimated_budget',
            'mode_of_procurement',
            'availability',
            'q1',
            'q2',
            'q3',
            'q4',
            'q5',
            'q6',
            'q7',
            'q8',
            'q9',
            'q10',
            'q11',
            'q12',
            'month',
            'active',
            'status_id',
            'supplemental',
            'date',
            'user_id',
        ],
    ]) ?>

</div>
