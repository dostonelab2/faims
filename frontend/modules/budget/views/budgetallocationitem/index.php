<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\budget\BudgetallocationitemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Budgetallocationitems';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budgetallocationitem-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Budgetallocationitem', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'budget_allocation_item_id',
            'budget_allocation_id',
            'name',
            'code',
            'category_id',
            // 'amount',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
