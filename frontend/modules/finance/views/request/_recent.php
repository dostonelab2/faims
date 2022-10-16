<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\grid\GridView;

use common\models\finance\Requestattachment;
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recent-requests">
    

    <!--?= $form->field($model, 'status_id')->textInput() ?-->
    <div class="container">
        <h3>Pending and Approved Requests for <b><?= $model->creditor->name ?></b></h3>    
        <p class="md-info">Please verify if similar request exists.</p>
    </div>
    
    
    <?php
        $gridColumns = [
                [
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '10px',
                    'header' => '',
                    //'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    //'mergeHeader' => true,
                ],
                [
                    'attribute' => 'request_number',
                    'label' => 'Request Number',
                    'width' => '100px',
                    'contentOptions' => ['style' => 'text-align: center; font-weight: bold;'],
                    'value'=>function ($model, $key, $index, $widget){ 
                                return $model->request_number;
                            }, 
                ],
                [
                    'attribute' => 'particulars',
                    'label' => 'Particulars',
                    'width' => '500px',
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: left; max-width:300px; overflow: auto; white-space: normal; word-wrap: break-word;'],
                    'value'=>function ($model, $key, $index, $widget){ 
                                return $model->particulars;
                            },
                ],
                [
                    'attribute' => 'status_id',
                    'label' => 'Status',
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'value'=>function ($model, $key, $index, $widget){ 
                                return $model->status->name;
                            },
                ],
                [
                    'attribute' => 'amount',
                    'label' => 'Amount',
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: right;'],
                    'format'=>['decimal', 2],
                    'value'=>function ($model, $key, $index, $widget){ 
                                return $model->amount;
                            },
                ],
                [
                    'attribute' => 'amount',
                    'label' => 'Attachments',
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: right;'],
                    'value'=>function ($model, $key, $index, $widget){ 
                                return '';
                            },
                ],
            ];
    ?>
    <?= GridView::widget([
                'id' => 'lddap-ada-items',
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => true, // pjax is set to always true for this demo
                // set left panel buttons
                'panel' => [
                    'heading'=>'<h3 class="panel-title">CREDITORS</h3>',
                    'type'=>'primary',
                ],
                // set right toolbar buttons
                'toolbar' => 
                                [
                                    [
                                        'content'=> '',
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

</div>
