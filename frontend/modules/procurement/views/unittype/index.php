<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\UnittypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Unittypes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unittype-index">

<h1 class="centered" style="margin-bottom: 0px;"><i class="fa fa-sitemap"></i> <?= Html::encode($this->title) ?></h1>
<br/>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Unittype', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'unit_type_id',
            'name_short',
            'name_long',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
