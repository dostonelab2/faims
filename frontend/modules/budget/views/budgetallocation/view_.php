<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\budget\Budgetallocation */

$this->title = $model->budget_allocation_id;
$this->params['breadcrumbs'][] = ['label' => 'Budgetallocations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budgetallocation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="ppmp-view">
   <?php
        $attributes = [
            [
                'group'=>true,
                'label'=>'PPMP DETAILS',
                'rowOptions'=>['class'=>'info'],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'unit_id',
                        'value'=>$model->section_id,
                        'valueColOptions'=>['style'=>'width:30%'],
                    ],
                    [
                        'attribute'=>'year',
                        'valueColOptions'=>['style'=>'width:30%'],
                        'label'=>'Year',
                    ],
                ],
            ],
            /*[
                'columns' => [
                    
                    [
                        'attribute'=>'division_id', 
                        'label'=>'Division',
                        'displayOnly'=>true,
                        'value'=>$model->division->name,
                        'valueColOptions'=>['style'=>'width:30%']
                    ],
                    [
                        'attribute'=>'status_id', 
                        'label'=>'Status',
                        'format'=>'raw', 
                        'value'=>$model->getStatus(),
                        'valueColOptions'=>['style'=>'width:30%'], 
                        'displayOnly'=>true
                    ],
                    
                ],
            ],*/
            [
                'columns' => [
                    [
                        'attribute'=>'project_id',
                        'valueColOptions'=>['style'=>'width:30%'],
                    ],
                    [
                        'attribute'=>'unit_id',
                        'valueColOptions'=>['style'=>'width:30%'],
                        'label'=>'Last Update',
                    ],
                ],
            ],
            [
                'group'=>true,
                'label'=>'BUDGET ALLOCATION',
                'rowOptions'=>['class'=>'info'],
                //'groupOptions'=>['class'=>'text-center']
            ],
            [
                'attribute'=>'charged_to',
                'label'=>'Approved Budget (Php)',
                'value'=>$model->getBudgetAllocation(),
                'format'=>['decimal', 2],
                'inputContainer' => ['class'=>'col-sm-6'],
            ],
            [
                'attribute'=>'year',
                'label'=>'Running Total (Php)',
                'value'=>$model->getRunningTotal(),
                'format'=>['decimal', 2],
                'inputContainer' => ['class'=>'col-sm-6'],
            ],
            [
                'label'=>'Remaining Balance (Php)',
                //'value'=>$model->buy_amount - $model->sale_amount,
                'value'=>$model->getBudgetAllocation() - $model->getRunningTotal(),
                'format'=>['decimal', 2],
                'inputContainer' => ['class'=>'col-sm-6'],
                // hide this in edit mode by adding `kv-edit-hidden` CSS class
                'rowOptions'=>['class'=>'warning kv-edit-hidden', 'style'=>'border-top: 5px double #dedede; texl-align: right;'],
            ],
        ];
        
        echo DetailView::widget([
            'model' => $model,
            'mode'=>DetailView::MODE_VIEW,
            /*'deleteOptions'=>[ // your ajax delete parameters
                'params' => ['id' => 1000, 'kvdelete'=>true],
            ],*/
            'container' => ['id'=>'kv-demo'],
            //'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
            
            'buttons1' => '', //hides buttons on detail view
            'attributes' => $attributes,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            'panel' => [
                //'type' => 'Primary', 
                'heading'=>'<i class="glyphicon glyphicon-book"></i> PPMP - '.$model->unit->name.' - '.$model->year,
                'type'=>DetailView::TYPE_PRIMARY,
                //'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
            ],
            
        ]);
    ?>
</div>


</div>
