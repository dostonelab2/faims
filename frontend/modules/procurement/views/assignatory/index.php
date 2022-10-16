<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\AssignatorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Report Configuration';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assignatory-index">
<h1 class="centered"><i class="fa fa-sitemap"></i> <?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Assignatory', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'report_title',
            'CompanyTitle',
            [
                'attribute'=>'assignatory_1',
                'label'=>'Assignatory 1',
                'enableSorting'=> 'false',
                'width'=>'60%',
                'value'=>function($data){
                    if ($data->assignatory_1=='') {
                        return '';
                    }else{
                        return $data->assig1->firstname . ' ' . $data->assig1->middleinitial .'. '. $data->assig1->lastname;  
                    }
                
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],
            [
                'attribute'=>'assignatory_2',
                'label'=>'Assignatory 2',
                'enableSorting'=> 'false',
                'width'=>'60%',
                'value'=>function($data){
                    if ($data->assignatory_2=='') {
                        return '';
                    }else{
                    return $data->assig2->firstname . ' ' . $data->assig2->middleinitial .'. '. $data->assig2->lastname;
                    }
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],
            [
                'attribute'=>'assignatory_3',
                'label'=>'Assignatory 3',
                'enableSorting'=> 'false',
                'width'=>'60%',
                'value'=>function($data){
                    if ($data->assignatory_3=='') {
                        return '';
                    }else{
                    return $data->assig3->firstname . ' ' . $data->assig3->middleinitial .'. '. $data->assig3->lastname;
                    }
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],

            [
                'attribute'=>'assignatory_4',
                'label'=>'Assignatory 4',
                'enableSorting'=> 'false',
                'width'=>'60%',
                'value'=>function($data){
                    if ($data->assignatory_4=='') {
                        return '';
                    }else{
                    return $data->assig4->firstname . ' ' . $data->assig4->middleinitial .'. '. $data->assig4->lastname;
                    }
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],

            [
                'attribute'=>'assignatory_5',
                'label'=>'Assignatory 5',
                'enableSorting'=> 'false',
                'width'=>'60%',
                'value'=>function($data){
                    if ($data->assignatory_5=='') {
                        return '';
                    }else{
                    return $data->assig5->firstname . ' ' . $data->assig5->middleinitial .'. '. $data->assig5->lastname;
                    }
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],

            [
                'attribute'=>'assignatory_6',
                'label'=>'Assignatory 6',
                'enableSorting'=> 'false',
                'width'=>'60%',
                'value'=>function($data){
                    if ($data->assignatory_6=='') {
                        return '';
                    }else{
                    return $data->assig6->firstname . ' ' . $data->assig6->middleinitial .'. '. $data->assig6->lastname;
                    }
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}{update}',  
            ],    
        ],
    ]); ?>
</div>
