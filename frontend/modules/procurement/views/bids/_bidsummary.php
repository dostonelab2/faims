<?php
use kartik\grid\GridView;


?>
<div id="bidsummary">

    <div class="row">
        <div class="col-lg-12">
            <?php

            $colorPluginOptions =  [
                'showPalette' => true,
                'showPaletteOnly' => true,
                'showSelectionPalette' => true,
                'showAlpha' => false,
                'allowEmpty' => false,
                'preferredFormat' => 'name',
                'palette' => [
                    [
                        "white", "black", "grey", "silver", "gold", "brown",
                    ],
                    [
                        "red", "orange", "yellow", "indigo", "maroon", "pink"
                    ],
                    [
                        "blue", "green", "violet", "cyan", "magenta", "purple",
                    ],
                ]
            ];

            $gridColumns = [

                [
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '5%',
                    'header' => '',
                    'headerOptions' => ['class' => 'kartik-sheet-style']
                ],
                [
                    'class' => 'kartik\grid\DataColumn',
                    'format'=>'html',
                    'header'=>'Remarks',
                    'value'=>function($model,$key,$index,$widget) {
                        switch ($model->bids_details_status) {
                            case 0:
                                return '<span class="badge btn-block" style="background: red;">For Award <i class="fa fa-remove"></i></span>';
                                break;
                            case 1:
                                return '<span class="badge btn-block" style="background: blue;">Awarded <i class="fa fa-check-circle"></i></span>';
                                break;
                            /*case 2:
                                return '<span class="badge btn-block" style="background: blue;">For Bids <i class="fa fa-check-circle"></i></span>';
                                break;
                            */
                        }
                    },
                    'headerOptions' => ['class' => '','style'=>'color:#337ab7;'],
                ],

                [
                    'attribute'=>'unit_id',
                    'label'=>'Unit',
                    'width'=>'10%',
                    'value'=>function ($model, $key, $index, $widget) {
                        return 'Units';
                    },
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                ],

                [
                    'attribute'=>'bids_item_description',
                    'label'=>'Item Description',
                    'width'=>'60%',
                    'value'=>function ($model, $key, $index, $widget) {
                        return $model->bids_item_description;
                    },
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                ],

                ['attribute'=>'bids_quantity',
                    'label'=> 'QTY',
                    'vAlign' => 'right',
                    'hAlign' => 'right',
                    'width' => '5%',
                    'pageSummary'=> true,
                    'headerOptions' => ['class' => '','style'=>'margin-left:100px!important;text-align:right!important;'],
                    'contentOptions'=> ['style'=>''],
                ],

                                                                                                            [
                                                                                                                'class' => 'kartik\grid\EditableColumn',
                                                                                                                'label'=> 'Price',
                                                                                                                'attribute' => 'bids_price',
                                                                                                                'pageSummary'=> true,
                                                                                                                'format' => ['decimal', 2],
                                                                                                                'refreshGrid'=>true,
                                                                                                                'readonly' => function($model, $key, $index, $widget) {
                                                                                                                    //return ($model->purchase_request_details_status==1); // do not allow editing of inactive records
                                                                                                                },
                                                                                                                'editableOptions' =>function ($model, $key, $index) {
                                                                                                                    return [
                                                                                                                        'header'=>'Price',
                                                                                                                        'format' => ['decimal', 2],
                                                                                                                        'convertFormat'=>true,
                                                                                                                        'size'=>'sm',
                                                                                                                        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                                                                                                                        'formOptions'=>['action' => ['bids/editPrice']], // point to the new action
                                                                                                                        'options'=>['pluginOptions'=>['min'=>0, 'max'=>50000000000,'step'=>100,'decimal'=>2 ,'prefix'=>'PHP']]
                                                                                                                    ];
                                                                                                                },

                                                                                                            ],


                [
                    'class' => 'kartik\grid\FormulaColumn',
                    'header' => 'Total Cost',
                    'vAlign' => 'middle',
                    'format' => ['decimal', 2],
                    //'attribute'=> 'purchase_request_details_total',
                    'value' => function ($model, $key, $index, $widget) {
                        $p = compact('model', 'key', 'index');
                        return $widget->col(4, $p) * $widget->col(5, $p);
                    },
                    'headerOptions' => ['class' => '','style'=>'color:#337ab7!important;'],
                    'hAlign' => 'right',
                    'width' => '10%',
                    'pageSummary' => true,

                ],

                [
                    'class' => 'kartik\grid\CheckboxColumn',
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'checkboxOptions' => function($model, $key, $index, $column) {
                        if($model->bids_details_status==2) {
                            $bool = false;
                            return ['checked' => $bool];
                        }else{
                            return ['checked' => $model->bids_details_status,
                                'disabled'=>true];
                        }
                    },
                ],



            ];

            echo GridView::widget([
                'id' => 'kv-grid-data',
                'dataProvider'=> $dataProvider,
                //'filterModel' => $searchModel,
                'pjax' => true,
                'columns' => $gridColumns,
                'pjaxSettings' => [
                    'options' => [
                        'enablePushState' => false,
                    ],
                ],

                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'toolbar' =>  [
                    ['content'=>''
                    ],
                    '{export}',
                    '{toggleData}',
                ],
                // set export properties
                'export' => [
                    'fontAwesome' => true
                ],

                'bordered' => true,
                'striped' => true,
                'condensed' => true,
                'responsive' => true,
                'hover' => true,
                'showPageSummary' => true,
                'panel' => [
                    'type' => GridView::TYPE_WARNING,
                    'heading' => '',
                ],
                'persistResize' => false,
                'toggleDataOptions' => ['minCount' => 5000],
                'exportConfig' => true,
            ]);
            ?>

        </div>
    </div>

</div>



