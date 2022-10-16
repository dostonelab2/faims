<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\OsallotmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Osallotments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="osallotment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Osallotment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'os_allotment_id',
            'osdv_id',
            'expenditure_class_id',
            'expenditure_object_id',
            'amount',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
