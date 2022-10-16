<?php
        echo GridView::widget([
            'id' => 'ppmp',
            'dataProvider' => $ppmpDataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                            //'section_id',
                            //'division_id',
                            [
                                'attribute'=>'division', 
                                'width'=>'250px',
                                'contentOptions' => ['style' => 'max-width:25px;white-space:normal;word-wrap:break-word;font-weight:bold'],
                                'value'=>function ($model, $key, $index, $widget) { 
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
                                'value'=>function($model,$key,$index,$widget) {
                                    return $model->getPpmp($model->section_id,2020);
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
                                    Html::dropDownList('name', 'null', ['2019' => '2019', '2020' => '2020'], ['data-pjax' => true, 'class' => 'btn btn-default']) 
                                    //Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => true, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
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