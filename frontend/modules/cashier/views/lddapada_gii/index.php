<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\cashier\LddapadaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lddapadas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lddapada-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Lddapada', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'lddapada_id',
            'batch_number',
            'batch_date',
            'certified_correct_id',
            'approved_id',
            // 'validated1_id',
            // 'validated2_id',
            // 'created_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
