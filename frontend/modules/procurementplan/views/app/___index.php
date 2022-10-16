<?php

use yii\helpers\Html;
use yii\helpers\Url;

use kartik\detail\DetailView;
use kartik\editable\Editable;
use kartik\grid\GridView;

use yii\bootstrap\Modal;

use common\models\procurementplan\Ppmp;

/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\Ppmp */

$this->title = 'Annual Procurement Plan - 2020';
$this->params['breadcrumbs'][] = ['label' => 'Ppmps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalSubmitPpmp',
    'size' => 'modal-sm',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalPpmpItem',
    'size' => 'modal-lg',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();
?>
<div class="ppmp-view">
   <?php
        /*$attributes = [
            [
                'group'=>true,
                'label'=>'PPMP DETAILS'.
                    Html::button('Submit PPMP  <i class="glyphicon glyphicon-hand-right"></i>', ['disabled' => $disableSubmitPpmp OR !$isMember, 'value' => Url::to(['ppmp/submit', 'id'=>$model->ppmp_id]), 'title' => 'Submit PPMP', 'class' => 'btn btn-primary', 'style'=>'float: right; margin-right: 6px; display: "";', 'id'=>'buttonSubmitPpmp'])
                    ,
                'rowOptions'=>['class'=>'info'],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'unit_id',
                        'value'=>$model->unit->name,
                        'valueColOptions'=>['style'=>'width:30%'],
                    ],
                    [
                        'attribute'=>'year',
                        'valueColOptions'=>['style'=>'width:30%'],
                        'label'=>'Year',
                    ],
                ],
            ],
            [
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
            ],
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
            
        ]);*/
    ?>
</div>

        <?php
            
            $gridColumns = [
                [
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '20px',
                    'header' => '',
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    //'mergeHeader' => true,
                ],
                [
                    'attribute'=>'item_category_id',
                    'header'=>'Category',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->itemcategory->category_name;
                        },
                    'headerOptions' => ['style' => 'text-align: left'],
                    'contentOptions' => ['style' => 'text-align: left'],
                
                    'group'=>true,  // enable grouping,
                    'groupedRow'=>true,                    // move grouped column to a single grouped row
                    'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                    'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                ],
                [
                    'attribute'=>'description', 
                    'header'=>'General Description',
                    'width'=>'650px',
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: left'],
                        'mergeHeader' => true,
                ],
                [
                    'attribute'=>'unit', 
                    'header'=>'Unit of Measure',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->unittype->name_short;
                        },
                    'width'=>'100px',
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: center'], 
                    'mergeHeader' => true,
                ],
                [
                    'attribute'=>'cost',
                    'header'=>'Unit Cost',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal($model->cost);
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q1',
                    'header'=>'J',
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->getMonthItemQuantity('q1');
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q2',
                    'header'=>'F',
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->getMonthItemQuantity('q2');
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q3',
                    'header'=>'M',
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->getMonthItemQuantity('q3');
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q4',
                    'header'=>'A',
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->getMonthItemQuantity('q4');
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q5',
                    'header'=>'M',
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->getMonthItemQuantity('q5');
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q6',
                    'header'=>'J',
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->getMonthItemQuantity('q6');
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q7',
                    'header'=>'J',
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->getMonthItemQuantity('q7');
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q8',
                    'header'=>'A',
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->getMonthItemQuantity('q8');
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q9',
                    'header'=>'S',
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->getMonthItemQuantity('q9');
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q10',
                    'header'=>'O',
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->getMonthItemQuantity('q10');
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q11',
                    'header'=>'N',
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->getMonthItemQuantity('q11');
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q12',
                    'header'=>'D',
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->getMonthItemQuantity('q12');
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'quantity',
                    'header'=>'QTY',
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->getItemQuantity();
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: center; font-weight: bold;'],
                ],
                [
                    'attribute'=>'estimated_budget',
                    'header'=>'Estimated Budget',
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal($model->getTotalamount());
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
            ];
            
            echo GridView::widget([
                'id' => 'ppmp-items',
                'dataProvider' => $ppmpItemsDataProvider,
                //'filterModel' => $searchModel,
                'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => true, // pjax is set to always true for this demo
                // set left panel buttons
                'panel' => [
                    'heading'=>'<h3 class="panel-title">Common Supplies and Equipment</h3>',
                    'type'=>'primary',
                    /*'before'=>Html::button('Add Items', ['value' => Url::to(['ppmpitem/additems', 'id'=>$model->ppmp_id, 'year'=>$model->year]), 'title' => 'PPMP Item', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonAddPpmpItem']),
                    'after'=>false,*/
                ],
                // set right toolbar buttons
                'toolbar' => 
                                [
                                    [
                                        'content'=> '',
                                            /*Html::button('Submit PPMP', ['value' => Url::to(['ppmp/submit', 'id'=>$model->ppmp_id]), 'title' => 'Submit PPMP', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonSubmitPpmp']).*/
                                            /*Html::button('Add Item  <i class="glyphicon glyphicon-list"></i>', ['disabled' => $disableAddItem OR !$isMember, 'value' => Url::to(['ppmpitem/additems', 'id'=>$model->ppmp_id, 'year'=>$model->year]), 'title' => 'PPMP Item', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonAddPpmpItem'])*/
                                    ],
                                ],
                // set export properties
                'export' => [
                    'fontAwesome' => true
                ],
                'persistResize' => false,
                'toggleDataOptions' => ['minCount' => 10],
                //'exportConfig' => $exportConfig,
                'itemLabelSingle' => 'item',
                'itemLabelPlural' => 'items'
            ]);
    
        ?>

