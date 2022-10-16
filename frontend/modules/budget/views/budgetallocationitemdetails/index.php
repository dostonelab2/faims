<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\budget\BudgetallocationitemdetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Budgetallocationitemdetails';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budgetallocationitemdetails-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Budgetallocationitemdetails', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'budget_allocation_item_detail_id',
            'budget_allocation_item_id',
            'fund_source_id',
            'program_id',
            'section_id',
            // 'name',
            // 'amount',
            // 'active',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
