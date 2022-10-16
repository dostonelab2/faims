<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\SectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sections';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="section-index">

<h1 class="centered" style="margin-bottom: 0px;"><i class="fa fa-sitemap"></i> <?= Html::encode($this->title) ?></h1>
<br/>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Section', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'section_id',
            [
                'attribute'=>'division_id',
                'label'=>'Division Name',
                'enableSorting'=> 'false',
                'width'=>'60%',
                'value'=>function($data){ 
                    return $data->division->name;
                },
            ],
            'code',
            'name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
