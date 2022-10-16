<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\budget\AllocationadjustmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Allocationadjustments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="allocationadjustment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Allocationadjustment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'allocation_adjustment_id',
            'item_id',
            'item_detail_id',
            'source_item',
            'amount',
            // 'create_date',
            // 'created_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
