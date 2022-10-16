<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\AccounttransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accounttransactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounttransaction-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Accounttransaction', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'account_transaction_id',
            'request_id',
            'account_id',
            'transaction_type',
            'amount',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
