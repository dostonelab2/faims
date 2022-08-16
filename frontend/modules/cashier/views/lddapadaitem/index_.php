<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\cashier\LddapadaitemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lddapadaitems';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lddapadaitem-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Lddapadaitem', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'lddapada_item_id',
            'lddapada_id',
            'creditor_id',
//            'type',
            'name',
            // 'bank_name',
            // 'account_number',
            // 'gross_amount',
            // 'alobs_id',
            // 'expenditure_object_id',
            // 'check_number',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
