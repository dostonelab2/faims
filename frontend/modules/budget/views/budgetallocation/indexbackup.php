<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\budget\BudgetallocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Budgetallocations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budgetallocation-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Budgetallocation', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'budget_allocation_id',
            'section_id',
            'year',
            'amount',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
