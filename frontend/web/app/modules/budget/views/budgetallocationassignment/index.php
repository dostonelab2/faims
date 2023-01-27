<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\budget\BudgetallocationassignmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Budgetallocationassignments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budgetallocationassignment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Budgetallocationassignment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'budget_allocation_assignment_id',
            'budget_allocation_id',
            'request_id',
            'budget_allocation_item_id',
            'budget_allocation_item_detail_id',
            // 'amount',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
