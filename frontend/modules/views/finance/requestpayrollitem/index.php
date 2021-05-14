<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\RequestpayrollitemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Requestpayrollitems';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requestpayrollitem-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Requestpayrollitem', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'request_payroll_item_id',
            'request_payroll_id',
            'request_id',
            'osdv_id',
            'dv_id',
            // 'creditor_id',
            // 'name',
            // 'particulars:ntext',
            // 'amount',
            // 'tax',
            // 'status_id',
            // 'osdv_attributes',
            // 'active',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
