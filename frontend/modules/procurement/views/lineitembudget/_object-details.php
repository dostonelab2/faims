<?php
use kartik\helpers\Html;
use yii\helpers\Url;

use kartik\grid\GridView; 
use kartik\editable\Editable; 
use yii\bootstrap\Modal;


echo GridView::widget([
                        'dataProvider'=> $dataProvider,
                        //'filterModel' => $searchModel,
                        'id' => 'lineitembudget-details-'.$model->line_item_budget_object_id,
                        'bordered' => true,
                        'pjax' => true,
                        'export' =>false,
                        'panel' => [
                            'heading' => '<b>Object Details</b>',
                            'type' => Gridview::TYPE_INFO,
                            //'before' => 'Before content', //IMPORTANT
                        ],
                        'toolbar' => 
                        [
                            [
                                'content'=>
                                Html::button('Add Object Details',   ['value' => Url::to(['lineitembudgetobjectdetails/create', 'id'=>$model->line_item_budget_object_id]), 'title' => 'Object Details', ['data-pjax' => true], 'class' => 'btn btn-PRIMARY', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddDetails'])
                            ],
                            '{export}',
                            '{toggleData}'
                        ],
                        'columns' => [
                            [
                                'attribute'=>'quantity', 
                                'width'=>'75px',
                                'contentOptions' => ['style' => 'text-align: center'], 
                            ],
                            [
                                'attribute'=>'objectDetail.name', 
                                'width'=>'450px',
                                'contentOptions' => ['style' => 'padding-left: 20px'], 
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->objectDetail->name;
                                },
                            ],
                            'details',
                            [
                                'attribute'=>'amount', 
                                'width'=>'250px',
                                'contentOptions' => ['style' => 'text-align: right'], 
                                'format'=>['decimal', 2],
                            ],
                        ],
                        'containerOptions' => ['style' => 'overflow: auto; margin: 20px;'], // only set when $responsive = false
                        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                        'pjax' => true, // pjax is set to always true for this demo
                        'responsive'=>true,
                        'hover'=>true,
                    ]);
?>
<pre>
    <?php //print_r($model);?>
</pre>