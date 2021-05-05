<?php

use yii\helpers\Html;
use yii\helpers\Url;

use kartik\detail\DetailView;
use kartik\editable\Editable;
use kartik\grid\GridView;

use yii\bootstrap\Modal;

use common\models\procurementplan\Ppmp;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
//use kartik\icons\FontAwesomeAsset;

/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\Ppmp */

$this->title = 'Annual Procurement Plan - 2020';
$this->params['breadcrumbs'][] = ['label' => 'Ppmps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>


<div class="ppmp-view">
<?php
echo $this->render('_selectyear',['model' => $searchModel]);
// Generate a bootstrap responsive striped table with row highlighted on hover
echo GridView::widget([
    'dataProvider'=> $ppmpItemsDataProvider,
    //'filterModel' => $searchModel,
    'pjax' => true, // pjax is set to always true for this demo
    /*
    'pjaxSettings' => [
                        'options' => [
                            'enablePushState' => false,
                            'id' => 'pjax-grid-view',
                        ],
                    ],*/
    'showPageSummary' => true,
    'columns' => [
               [
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '20px',
                    'header' => '#',
                    'headerOptions' => [
                        'class' => 'kartik-sheet-style',
                        'style' => 'text-align: left; background-color: #7e9fda;'
                    ],
                    //'mergeHeader' => true,
                ],
                [
                    'attribute'=>'availability',
                    'header'=>'Category',
                    'visible' =>  $ppmpItemsDataProvider->totalCount > 0 ? true : false,
                    'value'=>function ($model, $key, $index, $widget) { 
                            if($model->availability == 1){
                                return 'PART I. AVAILABLE AT PROCUREMENT SERVICE STORES';
                            }elseif($model->availability == 2){
                                return 'PART II. OTHER ITEMS NOT AVAILABLE AT PS BUT REGULARLY PURCHASED FROM OTHER SOURCES (Note: Please indicate price of items)';
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
                    'visible' =>  $ppmpItemsDataProvider->totalCount > 0 ? true : false,
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
                    'header'=>'Items & Specification',
                    'width'=>'650px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: left;'],
                    //'mergeHeader' => true,
                ],
                [
                    'attribute'=>'unit', 
                    'header'=>'Unit of Measure',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->unittype->name_short;
                        },
                    'width'=>'100px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: center'], 
                    //'mergeHeader' => true,
                ],
                [
                    'attribute'=>'jan',
                    'header'=>'Jan',
                    'width'=>'100px',
                    #'value'=>function ($model, $key, $index, $widget) { 
                           # return $model->getMonthItemQuantity('q1');
                        #},
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'feb',
                    'header'=>'Feb',
                    'width'=>'75px',
                    #'value'=>function ($model, $key, $index, $widget) { 
                           # return $model->getMonthItemQuantity('q2');
                        #},
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'mar',
                    'header'=>'Mar',
                    'width'=>'75px',
                    #'value'=>function ($model, $key, $index, $widget) { 
                            #return $model->getMonthItemQuantity('q3');
                        #},
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q1',
                    'header'=>'Q1',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right; background-color: #ededed'],
                ],
                [
                    'attribute'=>'q1amount',
                    'header'=>'Q1 AMOUNT',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right; background-color: #ededed'],
                ],
                [
                    'attribute'=>'apr',
                    'header'=>'Apr',
                    'width'=>'75px',
                    #'value'=>function ($model, $key, $index, $widget) { 
                           # return $model->getMonthItemQuantity('q4');
                        #},
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'may',
                    'header'=>'May',
                    'width'=>'75px',
                    #'value'=>function ($model, $key, $index, $widget) { 
                            #return $model->getMonthItemQuantity('q5');
                        #},
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'jun',
                    'header'=>'Jun',
                    'width'=>'75px',
                    #'value'=>function ($model, $key, $index, $widget) { 
                            #return $model->getMonthItemQuantity('q6');
                        #},
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q2',
                    'header'=>'Q2',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right; background-color: #ededed'],
                ],
                [
                    'attribute'=>'q2amount',
                    'header'=>'Q2 AMOUNT',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right; background-color: #ededed'],
                ],
                [
                    'attribute'=>'jul',
                    'header'=>'Jul',
                    'width'=>'75px',
                    #'value'=>function ($model, $key, $index, $widget) { 
                            #return $model->getMonthItemQuantity('q7');
                        #},
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'aug',
                    'header'=>'Aug',
                    'width'=>'75px',
                    #'value'=>function ($model, $key, $index, $widget) { 
                            #return $model->getMonthItemQuantity('q8');
                        #},
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'sep',
                    'header'=>'Sep',
                    'width'=>'75px',
                    #'value'=>function ($model, $key, $index, $widget) { 
                            #return $model->getMonthItemQuantity('q9');
                        #},
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q3',
                    'header'=>'Q3',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right; background-color: #ededed'],
                ],
                [
                    'attribute'=>'q3amount',
                    'header'=>'Q3 AMOUNT',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right; background-color: #ededed'],
                ],
                [
                    'attribute'=>'oct',
                    'header'=>'Oct',
                    'width'=>'75px',
                    #'value'=>function ($model, $key, $index, $widget) { 
                            #return $model->getMonthItemQuantity('q10');
                        #},
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'nov',
                    'header'=>'Nov',
                    'width'=>'75px',
                    #'value'=>function ($model, $key, $index, $widget) { 
                            #return $model->getMonthItemQuantity('q11');
                        #},
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'dec',
                    'header'=>'Dec',
                    'width'=>'75px',
                    #'value'=>function ($model, $key, $index, $widget) { 
                            #return $model->getMonthItemQuantity('q12');
                        #},
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'q4',
                    'header'=>'Q4',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right; background-color: #ededed'],
                ],
                [
                    'attribute'=>'q4amount',
                    'header'=>'Q4 AMOUNT',
                    'width'=>'75px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right; background-color: #ededed'],
                ],
                [
                    'attribute'=>'quantity',
                    'header'=>'Total Quantity for the year',
                    'width'=>'75px',
                    #'value'=>function ($model, $key, $index, $widget) { 
                            #return $model->getItemQuantity();
                        #},
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: center; font-weight: bold;'],
                ],
                [
                    'attribute'=>'cost',
                    'header'=>'Price Catalogue',
                    'width'=>'100px',
                    #'value'=>function ($model, $key, $index, $widget) { 
                            #$fmt = Yii::$app->formatter;
                            #return $fmt->asDecimal($model->cost);
                        #},
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right'],
                    'pageSummary' => 'TOTAL'
                ],
                [
                    'attribute'=>'totalamount',
                    'header'=>'Total Amount for the year',
                    'width'=>'75px',
                    /*
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal($model->getTotalamount());
                        },*/
                    'mergeHeader' => true,
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
                    'contentOptions' => ['style' => 'text-align: right; background-color: #ededed'],
                    'format' => ['decimal', 2],
                    'pageSummary' => true
                ],
            ],
                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => true, // pjax is set to always true for this demo
                // set left panel buttons
                'panel' => [
                    'heading'=>'<h3 class="panel-title">Annual Procurement Plan</h3>',
                    'type'=>'primary',
                    
                ],
                // set right toolbar buttons
                'toolbar' => [
                    /*'content' =>     $form->field($searchModel, 'year')->dropDownList([
                                                    '2020' => 'YEAR-2020',
                                                    '2021' => 'YEAR-2021',
                                                ],
                                                [
                                                    'prompt' => 'Select Year...',
                                                    //'onchange' => 'selectMonth(this.value)',
                                                    'id' => 'dropdown',
                                                    //'onchange'=>'this.form.submit()'
                                                ]
                                                )->label(false)
                            /*Select2::widget([
                                        'model' => $searchModel,
                                        'attribute' => 'year',
                                        'name' => 'combobox',
                                        'id' => 'dropdown',

                                        'data' => [
                                            '2020' => 'YEAR-2020',
                                            '2021' => 'YEAR-2021'],
                                       'options' => [
                                            'placeholder' => 'Select year...',
                                            //'multiple' => true
                                        ],
                                        'pluginOptions' => [
                                            //'multiple' => true,
                                            'width' => '150px',
                                            'style' => 'text-align: right;',
                                            'allowClear' => true,
                                            ],
                                    ]),*/
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

//FontAwesomeAsset::register($this);
?>


