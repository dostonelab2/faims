<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\OsdvSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Osdvs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="osdv-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Osdv', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'osdv_id',
            'request_id',
            'type_id',
            'expenditure_class_id',
            'status_id',
            // 'created_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
