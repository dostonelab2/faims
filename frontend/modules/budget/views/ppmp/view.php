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

$this->title = $model->unit->name;
$this->params['breadcrumbs'][] = ['label' => 'BUDGET', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'PPMP', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalApprovePpmp',
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
        $attributes = [
            [
                'group'=>true,
                'label'=>'PPMP DETAILS'.
                    Html::button('Approve PPMP  <i class="glyphicon glyphicon-hand-right"></i>', ['disabled' => $disableApprovePpmp, 'value' => Url::to(['ppmp/approve', 'id'=>$model->ppmp_id]), 'title' => 'Submit PPMP', 'class' => 'btn btn-primary', 'style'=>'float: right; margin-right: 6px; display: "";', 'id'=>'buttonApprovePpmp'])
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
                            return $model->unitofmeasure->name;
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
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q1',
                    'header'=>'J',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q1'],
                            'placement'=>'left',
                            //'disabled'=>(($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q1',
                            'asPopover' => true,
                            'value' => $model->q1.' - '.$model->ppmp->isPending(),
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/budget/ppmp/updateqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q2',
                    'header'=>'F',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q2'],
                            'placement'=>'left',
                            //'disabled'=>(($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q2',
                            'asPopover' => true,
                            'value' => $model->q2,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/budget/ppmp/updateqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q3',
                    'header'=>'M',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q3'],
                            'placement'=>'left',
                            //'disabled'=>(($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q3',
                            'asPopover' => true,
                            'value' => $model->q3,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/budget/ppmp/updateqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q4',
                    'header'=>'A',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q4'],
                            'placement'=>'left',
                            //'disabled'=>(($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q4',
                            'asPopover' => true,
                            'value' => $model->q4,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/budget/ppmp/updateqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q5',
                    'header'=>'M',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q5'],
                            'placement'=>'left',
                            //'disabled'=>(($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q5',
                            'asPopover' => true,
                            'value' => $model->q5,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/budget/ppmp/updateqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q6',
                    'header'=>'J',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q6'],
                            'placement'=>'left',
                            //'disabled'=>(($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q6',
                            'asPopover' => true,
                            'value' => $model->q6,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/budget/ppmp/updateqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q7',
                    'header'=>'J',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q7'],
                            'placement'=>'left',
                            //'disabled'=>(($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q7',
                            'asPopover' => true,
                            'value' => $model->q7,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/budget/ppmp/updateqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q8',
                    'header'=>'A',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q8'],
                            'placement'=>'left',
                            //'disabled'=>(($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q8',
                            'asPopover' => true,
                            'value' => $model->q8,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/budget/ppmp/updateqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q9',
                    'header'=>'S',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q9'],
                            'placement'=>'left',
                            //'disabled'=>(($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q9',
                            'asPopover' => true,
                            'value' => $model->q9,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/budget/ppmp/updateqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q10',
                    'header'=>'O',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q10'],
                            'placement'=>'left',
                            //'disabled'=>(($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q10',
                            'asPopover' => true,
                            'value' => $model->q10,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/budget/ppmp/updateqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q11',
                    'header'=>'N',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q11'],
                            'placement'=>'left',
                            //'disabled'=>(($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q11',
                            'asPopover' => true,
                            'value' => $model->q11,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/budget/ppmp/updateqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q12',
                    'header'=>'D',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q12'],
                            'placement'=>'left',
                            //'disabled'=>(($model->ppmp->status_id != Ppmp::STATUS_PENDING) OR !$model->ppmp->isMember(),
                            'name'=>'q12',
                            'asPopover' => true,
                            'value' => $model->q12,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/budget/ppmp/updateqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'attribute'=>'quantity',
                    'header'=>'QTY',
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->getTotalqty();
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: center'],
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
                                        'content'=>'',
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

