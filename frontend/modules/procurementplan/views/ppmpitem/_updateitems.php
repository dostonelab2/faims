<?php

use yii\helpers\Html;
use yii\helpers\Url;

use kartik\detail\DetailView;
use kartik\editable\Editable;
use kartik\grid\GridView;

use yii\bootstrap\Modal;

use common\models\procurementplan\Ppmp;
use common\models\procurement\Project;

/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\Ppmp */
?>
        <?php
            
            $gridColumns = [
                [
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '20px',
                    'header' => '',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    //'mergeHeader' => true,
                ],
                [
                    'attribute'=>'availability',
                    'header'=>'Category',
                    'value'=>function ($model, $key, $index, $widget) { 
                            if($model->availability == 1){
                                return 'PART I. AVAILABLE AT PROCUREMENT SERVICE STORES';
                            }elseif($model->availability == 2){
                                return 'PART II. OTHER ITEMS NOT AVALABLE AT PS BUT REGULARLY PURCHASED FROM OTHER SOURCES (Note: Please indicate price of items)';
                            }
                        },
                    'headerOptions' => ['style' => 'background-color: #fee082;'],
                    'contentOptions'=>['style'=>'background-color: #fee082; font-weight: bold;'],
                
                    'group'=>true,  // enable grouping,
                    'groupedRow'=>true,                    // move grouped column to a single grouped row
                    //'contentOptions' => ['style' => 'text-align: left; background-color: #ffe699;'],
                    
                    'groupOddCssClass'=>'',  // configure odd group cell css class
                    'groupEvenCssClass'=>'', // configure even group cell css class
                ],
                [
                    'attribute'=>'item_category_id',
                    'header'=>'Category',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->itemcategory->category_name;
                        },
                    'headerOptions' => ['style' => 'text-align: left; background-color: #7e9fda;'],
                    'contentOptions' => ['style' => 'text-align: left; background-color: #7e9fda;'],
                
                    'group'=>true,  // enable grouping,
                    'groupedRow'=>true,                    // move grouped column to a single grouped row
                    'groupOddCssClass'=>'',  // configure odd group cell css class
                    'groupEvenCssClass'=>'', // configure even group cell css class
                ],
                [
                    'attribute'=>'description', 
                    'header'=>'General Description',
                    'width'=>'650px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: left'],
                    'mergeHeader' => true,
                ],
                [
                    'attribute'=>'unit', 
                    'header'=>'Unit of Measure',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->unitofmeasure->name ? $model->unitofmeasure->name : '';
                        },
                    'width'=>'100px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
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
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q1',
                    'header'=>'J',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->q1;
                        },
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: center'],
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q1',
                    'header'=>'J\'',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q1'],
                            'placement'=>'left',
                            //'disabled'=>($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q1',
                            'asPopover' => false,
                            'value' => $model->q1.' - '.$model->ppmp->isPending(),
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/procurementplan/ppmp/updateitemqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'attribute'=>'q2',
                    'header'=>'F',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->q2;
                        },
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: center'],
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q2',
                    'header'=>'F\'',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q2'],
                            'placement'=>'left',
                            //'disabled'=>($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q2',
                            'asPopover' => false,
                            'value' => $model->q2,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/procurementplan/ppmp/updateitemqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'attribute'=>'q3',
                    'header'=>'M',
                    'width'=>'50px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->q3;
                        },
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: center'],
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q3',
                    'header'=>'M\'',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q3'],
                            'placement'=>'left',
                            //'disabled'=>($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q3',
                            'asPopover' => false,
                            'value' => $model->q3,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/procurementplan/ppmp/updateitemqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'attribute'=>'q4',
                    'header'=>'A',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->q4;
                        },
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: center'],
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q4',
                    'header'=>'A\'',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q4'],
                            'placement'=>'left',
                            //'disabled'=>($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q4',
                            'asPopover' => false,
                            'value' => $model->q4,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/procurementplan/ppmp/updateitemqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'attribute'=>'q5',
                    'header'=>'M',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->q5;
                        },
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: center'],
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q5',
                    'header'=>'M\'',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q5'],
                            'placement'=>'left',
                            //'disabled'=>($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q5',
                            'asPopover' => false,
                            'value' => $model->q5,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/procurementplan/ppmp/updateitemqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'attribute'=>'q6',
                    'header'=>'J',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->q6;
                        },
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: center'],
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q6',
                    'header'=>'J\'',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q6'],
                            'placement'=>'left',
                            //'disabled'=>($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q6',
                            'asPopover' => false,
                            'value' => $model->q6,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/procurementplan/ppmp/updateitemqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'attribute'=>'q7',
                    'header'=>'J',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->q7;
                        },
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: center'],
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q7',
                    'header'=>'J\'',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q7'],
                            'placement'=>'left',
                            //'disabled'=>($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q7',
                            'asPopover' => false,
                            'value' => $model->q7,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/procurementplan/ppmp/updateitemqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'attribute'=>'q8',
                    'header'=>'A',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->q8;
                        },
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: center'],
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q8',
                    'header'=>'A\'',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q8'],
                            'placement'=>'left',
                            //'disabled'=>($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q8',
                            'asPopover' => false,
                            'value' => $model->q8,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/procurementplan/ppmp/updateitemqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'attribute'=>'q9',
                    'header'=>'S',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->q9;
                        },
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: center'],
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q9',
                    'header'=>'S\'',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q9'],
                            'placement'=>'left',
                            //'disabled'=>($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q9',
                            'asPopover' => false,
                            'value' => $model->q9,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/procurementplan/ppmp/updateitemqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'attribute'=>'q10',
                    'header'=>'O',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->q10;
                        },
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: center'],
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q10',
                    'header'=>'O\'',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q10'],
                            'placement'=>'left',
                            //'disabled'=>($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q10',
                            'asPopover' => false,
                            'value' => $model->q10,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/procurementplan/ppmp/updateitemqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'attribute'=>'q11',
                    'header'=>'N',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->q11;
                        },
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: center'],
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q11',
                    'header'=>'N\'',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q11'],
                            'placement'=>'left',
                            //'disabled'=>($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'q11',
                            'asPopover' => false,
                            'value' => $model->q11,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/procurementplan/ppmp/updateitemqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'attribute'=>'q12',
                    'header'=>'D',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->q12;
                        },
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: center'],
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'q12',
                    'header'=>'D\'',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q12'],
                            'placement'=>'left',
                            //'disabled'=>($model->ppmp->status_id != Ppmp::STATUS_PENDING) OR !$model->ppmp->isMember(),
                            'name'=>'q12',
                            'asPopover' => false,
                            'value' => $model->q12,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/procurementplan/ppmp/updateitemqty']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'attribute'=>'quantity',
                    'header'=>'QTY',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->getTotalqty();
                        },
                    //'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: center'],
                ],
                [
                    'attribute'=>'estimated_budget',
                    'header'=>'Estimated Budget',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal($model->getTotalamount());
                        },
                    //'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
            ];
            
            echo GridView::widget([
                'id' => 'ppmp-items-adjustment',
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
                'toolbar' => '',
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