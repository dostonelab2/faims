<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\RequesttypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Requesttypes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requesttype-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Requesttype', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'request_type_id',
            'name',
            //'default_text',
            [
                'attribute'=>'default_text',
                //'width'=>'450px',
                'contentOptions' => [
                    'style'=>'max-width:300px; overflow: auto; white-space: normal; word-wrap: break-word;'
                ],
                /*'format' => 'raw',
                'value'=>function ($model, $key, $index, $widget) { 
                    return '<b>' . Creditor::find($model->payee_id)->one()->name. '</b><br>' .$model->particulars;
                },*/
            ],
            //'active',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
