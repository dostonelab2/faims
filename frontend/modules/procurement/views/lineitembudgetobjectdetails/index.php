<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\LineitembudgetobjectdetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lineitembudgetobjectdetails';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lineitembudgetobjectdetails-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Lineitembudgetobjectdetails', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'line_item_budget_object_details_id',
            'line_item_budget_object_id',
            'object_detail_id',
            'quantity',
            'name',
            // 'details:ntext',
            // 'amount',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
