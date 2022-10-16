<?php
use kartik\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use kartik\datecontrol\DateControl;
use kartik\detail\DetailView;
use kartik\editable\Editable; 
use kartik\grid\GridView;

use yii\bootstrap\Modal;

use common\models\procurementplan\Section;
use common\models\procurementplan\Ppmp;
use common\models\procurementplan\PpmpSearch;

//use common\models\procurement\Division;
/* @var $this yii\web\View */
/* @var $searchModel common\models\procurementplan\PpmpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ppmps';
$this->params['breadcrumbs'][] = $this->title;

// Modal PPMP
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalPpmp',
    'size' => 'modal-md',
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
    <div class="ppmp-index">
    <?php //echo $selected_year; ?>
    <h3 style="text-align: center"><?= Html::encode('PROJECT PROCUREMENT MANAGEMENT PLAN (PPMP)') ?></h3>

   
   
   <?php
        echo GridView::widget([
            'id' => 'ppmp',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                            'ppmp_id',
                            [
                                'attribute'=>'division', 
                                'width'=>'250px',
                                'contentOptions' => ['style' => 'max-width:25px;white-space:normal;word-wrap:break-word;font-weight:bold'],
                                'value'=>function ($model, $key, $index, $widget) use ($selected_year) { 
                                    return $model->division->name;
                                },
                                'group'=>true,  // enable grouping,
                                'groupedRow'=>true,                    // move grouped column to a single grouped row
                                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                            ],
                            'division_id',
                            'year',
                            /*[
                                'attribute'=>'division', 
                                'width'=>'250px',
                                'contentOptions' => ['style' => 'max-width:25px;white-space:normal;word-wrap:break-word;font-weight:bold'],
                                'value'=>function ($model, $key, $index, $widget) use ($selected_year) { 
                                    return $model->division->name;
                                },
                                'group'=>true,  // enable grouping,
                                'groupedRow'=>true,                    // move grouped column to a single grouped row
                                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                            ],
                            [
                                'attribute'=>'name',
                                'contentOptions' => ['style' => 'padding-left: 25px'],
                                'width'=>'250px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->name;
                                },
                            ],
                            
                            [
                                'attribute'=>'ppmp',
                                'header'=>'Status',
                                'width'=>'250px',
                                
                                'value'=>function($model,$key,$index,$widget) use ($selected_year) {
                                    //return $model->getPpmp($model->section_id,$selected_year);
                                },
                            ],*/
                            [
                                'class' => '\kartik\grid\ActionColumn',
                                'template' => '{view}{update}{create}',
                                'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>'],
                                'buttons' => 
                                    [
                                        
                                    ]
                            ]
                    ],
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                //'heading' => $heading,
            ],
            // set your toolbar
            'toolbar' => 
                        [
                            [
                                'content'=>
                                    Html::button('New PPMP', ['value' => Url::to(['ppmp/create']), 'title' => 'PPMP', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddPpmp']) . ' ' .
                                    Html::dropDownList('name', $selected_year, ['2019' => '2019', '2020' => '2020'], 
                                                       [
                                                            'data-pjax' => true, 
                                                            'class' => 'btn btn-default',
                                                            'id' => 'year',
                                                       ])
                            ],
                            //'{export}',
                            //'{toggleData}'
                        ],
            
            'toggleDataOptions' => ['minCount' => 10],
            //'exportConfig' => $exportConfig,
            'itemLabelSingle' => 'item',
            'itemLabelPlural' => 'items'
        ]);
        
        ?>
   
        <?php
        echo GridView::widget([
            'id' => 'ppmp3',
            'dataProvider' => $ppmpDataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                            //'section_id',
                            //'division_id',
                            [
                                'attribute'=>'division', 
                                'width'=>'250px',
                                'contentOptions' => ['style' => 'max-width:25px;white-space:normal;word-wrap:break-word;font-weight:bold'],
                                'value'=>function ($model, $key, $index, $widget) use ($selected_year) { 
                                    return $model->division->name;
                                },
                                'group'=>true,  // enable grouping,
                                'groupedRow'=>true,                    // move grouped column to a single grouped row
                                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                            ],
                            [
                                'attribute'=>'name',
                                'contentOptions' => ['style' => 'padding-left: 25px'],
                                'width'=>'250px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->name;
                                },
                            ],
                            
                            [
                                'attribute'=>'ppmp',
                                'header'=>'Status',
                                'width'=>'250px',
                                /*'value'=>function ($model) { 
                                    //return print_r($data->getPpmp($model->section_id, 2019));
                                    return $this->ppmpDetails();
                                },*/
                                'value'=>function($model,$key,$index,$widget) use ($selected_year) {
                                    //return $model->getPpmp($model->section_id,$selected_year);
                                },
                            ],
                            [
                                'class' => '\kartik\grid\ActionColumn',
                                'template' => '{view}{update}{create}',
                                'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>'],
                                'buttons' => 
                                    [
                                        
                                    ]
                            ]
                    ],
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                //'heading' => $heading,
            ],
            // set your toolbar
            'toolbar' => 
                        [
                            [
                                'content'=>
                                    Html::button('New PPMP', ['value' => Url::to(['ppmp/create']), 'title' => 'PPMP', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddPpmp']) . ' ' .
                                    Html::dropDownList('name', $selected_year, ['2019' => '2019', '2020' => '2020'], 
                                                       [
                                                            'data-pjax' => true, 
                                                            'class' => 'btn btn-default',
                                                            'id' => 'year',
                                                       ])
                            ],
                            //'{export}',
                            //'{toggleData}'
                        ],
            
            'toggleDataOptions' => ['minCount' => 10],
            //'exportConfig' => $exportConfig,
            'itemLabelSingle' => 'item',
            'itemLabelPlural' => 'items'
        ]);
        
        ?>
    
    <?php
    $gridColumns = [
        [
            'class' => 'kartik\grid\SerialColumn',
            'contentOptions' => ['class' => 'kartik-sheet-style'],
            'width' => '36px',
            'header' => '',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'mergeHeader' => true,
        ],
        [
            'attribute'=>'ppmp_item_category_id',
            'header'=>'Category',
            'width'=>'100px',
            'headerOptions' => ['style' => 'text-align: center'],
            'contentOptions' => ['style' => 'text-align: center'],
            'mergeHeader' => true,
        ],[
            'attribute'=>'code',
            'header'=>'Code',
            'width'=>'75px',
            'contentOptions' => ['style' => 'text-align: center'],
            'mergeHeader' => true,
        ],
        [
            'attribute'=>'description', 
            'header'=>'General Description',
            'width'=>'300px',
            'headerOptions' => ['style' => 'text-align: center'],
            'contentOptions' => ['style' => 'text-align: left'],
                'mergeHeader' => true,
        ],
        [
            'attribute'=>'unit', 
            'header'=>'Unit of Measure',
            'width'=>'75px',
            'headerOptions' => ['style' => 'text-align: center'],
            'contentOptions' => ['style' => 'text-align: center'], 
            'mergeHeader' => true,
        ],
        [
            'attribute'=>'cost', 
            'header'=>'Unit Cost',
            'width'=>'75px',
            'headerOptions' => ['style' => 'text-align: center'],
            'contentOptions' => ['style' => 'text-align: center'], 
            'mergeHeader' => true,
        ],
        [
            'attribute'=>'q1', 
            'header'=>'Jan',
            'width'=>'75px',
            'contentOptions' => ['style' => 'text-align: center'], 
            //'mergeHeader' => true,
        ],
        [
            'attribute'=>'q2', 
            'header'=>'Feb',
            'width'=>'30px',
            'contentOptions' => ['style' => 'text-align: center'], 
            //'mergeHeader' => true,
        ],
        [
            'attribute'=>'q3', 
            'header'=>'Mar',
            'width'=>'30px',
            'contentOptions' => ['style' => 'text-align: center'], 
            //'mergeHeader' => true,
        ],
        [
            'attribute'=>'q4', 
            'header'=>'Apr',
            'width'=>'30px',
            'contentOptions' => ['style' => 'text-align: center'], 
            //'mergeHeader' => true,
        ],
        [
            'attribute'=>'q5', 
            'header'=>'May',
            'width'=>'30px',
            'contentOptions' => ['style' => 'text-align: center'], 
            //'mergeHeader' => true,
        ],
        [
            'attribute'=>'q6', 
            'header'=>'Jun',
            'width'=>'30px',
            'contentOptions' => ['style' => 'text-align: center'], 
            //'mergeHeader' => true,
        ],
        [
            'attribute'=>'q7', 
            'header'=>'Jul',
            'width'=>'30px',
            'contentOptions' => ['style' => 'text-align: center'], 
            //'mergeHeader' => true,
        ],
        [
            'attribute'=>'q8', 
            'header'=>'Aug',
            'width'=>'30px',
            'contentOptions' => ['style' => 'text-align: center'], 
            //'mergeHeader' => true,
        ],
        [
            'attribute'=>'q9', 
            'header'=>'Sep',
            'width'=>'30px',
            'contentOptions' => ['style' => 'text-align: center'], 
            //'mergeHeader' => true,
        ],
        [
            'attribute'=>'q10', 
            'header'=>'Oct',
            'width'=>'30px',
            'contentOptions' => ['style' => 'text-align: center'], 
            //'mergeHeader' => true,
        ],
        [
            'attribute'=>'q11', 
            'header'=>'Nov',
            'width'=>'30px',
            'contentOptions' => ['style' => 'text-align: center'], 
            //'mergeHeader' => true,
        ],
        [
            'attribute'=>'q12', 
            'header'=>'Dec',
            'width'=>'30px',
            'contentOptions' => ['style' => 'text-align: center'], 
            //'mergeHeader' => true,
        ],
        [
            'attribute'=>'quantity', 
            'header'=>'Quantity',
            'width'=>'75px',
            'contentOptions' => ['style' => 'text-align: center'], 
            'mergeHeader' => true,
        ],
        [
            'attribute'=>'estimated_budget', 
            'header'=>'Amount',
            'width'=>'75px',
            'contentOptions' => ['style' => 'text-align: center'], 
            'mergeHeader' => true,
        ]
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
        // set your toolbar
        'toolbar' => 
                        [
                            [
                                'content'=>
                                    Html::button('Add Item', ['value' => Url::to(['ppmpitem/create']), 'title' => 'PPMP Item', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddPpmpItem'])// .
                                    //Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => true, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
                            ],
                            //'{export}',
                            //'{toggleData}'
                        ],
       /* 'toolbar' =>  [
            ['content' => 
                Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type' => 'button', 'title' =>'Add Items', 'class' => 'btn btn-success', 'onclick' => 'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) . ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['grid-demo'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => 'Reset Grid'])
            ],
            '{export}',
            '{toggleData}',
        ],*/
        // set export properties
        'export' => [
            'fontAwesome' => true
        ],
        // parameters from the demo form
        //'bordered' => $bordered,
        //'striped' => $striped,
        //'condensed' => $condensed,
        //'responsive' => $responsive,
        //'hover' => $hover,
        //'showPageSummary' => $pageSummary,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            //'heading' => $heading,
        ],
        'persistResize' => false,
        'toggleDataOptions' => ['minCount' => 10],
        //'exportConfig' => $exportConfig,
        'itemLabelSingle' => 'item',
        'itemLabelPlural' => 'items'
    ]);
    ?>
</div>


<pre>
    <?php 
        //print_r($dataProvider);
    ?>
</pre>