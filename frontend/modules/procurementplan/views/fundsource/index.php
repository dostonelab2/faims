<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\FundsourceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fundsources';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fundsource-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Fundsource', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'fund_source_id',
            'code',
            'name:ntext',
            'active',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
