<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurementplan\PpmpitemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ppmpitems';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ppmpitem-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Ppmpitem', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ppmp_item_id',
            'ppmp_id',
            //'ppmp_item_category_id',
            'code',
            'description:ntext',
            // 'item_specification:ntext',
            // 'quantity',
            // 'unit',
            // 'cost',
            // 'estimated_budget',
            // 'mode_of_procurement',
            // 'q1',
            // 'q2',
            // 'q3',
            // 'q4',
            // 'q5',
            // 'q6',
            // 'q7',
            // 'q8',
            // 'q9',
            // 'q10',
            // 'q11',
            // 'q12',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
