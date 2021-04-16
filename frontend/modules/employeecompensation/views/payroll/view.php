<?php

use yii\helpers\Html;
use yii\helpers\Url;

use kartik\detail\DetailView;
use kartik\editable\Editable;
use kartik\grid\GridView;

use yii\bootstrap\Modal;

use common\models\procurementplan\Ppmp;
use common\models\cashier\Checknumber;
use common\models\cashier\Lddapada;
use common\models\cashier\Lddapadaitem;
use common\models\finance\Accounttransaction;

$this->title = $model->payroll_id;
$this->params['breadcrumbs'][] = ['label' => 'Payroll', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payroll-view">

    <?php $attributes = [
            [
                'group'=>true,
                'rowOptions'=>['class'=>'info'],
            ],
    
            /**
            'payroll_id',
            'obligation_type_id',
            'payroll_date',
            'created_by',
            **/
            [
                'columns' => [
                    [
                        'attribute'=>'payroll_id',
                        'label' => 'Entity Name',
                        'labelColOptions'=>['style'=>'text-align: left; width: 8%;'],
                        'value'=>'Department of Science and Technology IX',
                        'valueColOptions'=>['style'=>'width:70%'],
                    ],
                    [
                        'attribute'=>'payroll_id',
                        'format'=>'raw',
                        'value'=>'<kbd>1234567890</kbd>',
                        'valueColOptions'=>['style'=>'width:10%; font-size:18px; font-weight: bold;'],
                        'labelColOptions'=>['style'=>'text-align: left; width: 8%;'],
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'payroll_id',
                        'label'=>'Fund Cluster',
                        'labelColOptions'=>['style'=>'text-align: left; width: 8%;'],
                        'value' => 'Regular Fund',
                        'valueColOptions'=>['style'=>'width:70%'],
                        
                    ],
                    [
                        'attribute'=>'payroll_id',
                        //'value'=>date('m/d/Y', strtotime($model->batch_date)),
                        'valueColOptions'=>['style'=>'width:20%'],
                        'label'=>'Date',
                        'labelColOptions'=>['style'=>'text-align: left; width: 8%;'],
                    ],
                ],
            ],
                        [
                'columns' => [
                    [
                        'attribute'=>'payroll_id',
                        'value' => '',
                        'valueColOptions'=>['style'=>'width:30%'],
                        'label'=>'We acknowledge receipt of cash shown opposite our name as full compensation for services rendered for the period covered.',
                        'labelColOptions'=>['style'=>'text-align: left; font-weight: normal;'],
                        //'inputContainer' => ['class'=>'col-sm-6'],
                    ],
                ],
            ],
        ];?>
    <?= DetailView::widget([
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
                'heading'=>'<center>PAYROLL<BR/>For the  period</center>',
                'type'=>DetailView::TYPE_PRIMARY,
                //'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
            ],
            
        ]); ?>
        
        
        
        
        
    <?php
        $gridColumns = [
                [
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '20px',
                    'header' => '',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'pageSummary'=>'Total',
                    'pageSummaryOptions' => ['colspan' => 5],
                ],

                [   
                    'attribute'=>'payroll_item_id',
                    'header' => 'NAME',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: left; padding-left: 10px; vertical-align: middle;'],
                    'format' => 'raw',
                    'width'=>'80px',
                    /*'value'=>function ($model, $key, $index, $widget) { 
                        return Requestattachment::generateCode($model->request_attachment_id);
                    },*/
                ],
/*
                [   
                    'attribute'=>'account_number',
                    'header' => 'PREFERRED<br/>SERVICING BANK<br/>SAVINGS/CURRENT<br/>ACCOUNT NO.',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'format' => 'raw',
                    'width'=>'150px',
                    'value'=>function ($model, $key, $index, $widget) { 
                        return $model->osdv->request->creditor->account_number;
                    },
                ],
                [   
                    'attribute'=>'alobs_id',
                    'header' => 'Obligation<br/>Request and<br/>Status No.',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'format' => 'raw',
                    'width'=>'150px',
                    'value'=>function ($model, $key, $index, $widget) { 
                    
                        return $model->osdv->os ? $model->osdv->os->os_number : $model->osdv->dv->dv_number;
                    },
                ],
                [   
                    'attribute'=>'alobs_id',
                    'header' => 'ALLOTMENT<br/>CLASS per<br/>(UACS)',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'format' => 'raw',
                    'width'=>'150px',
                    'value'=>function ($model, $key, $index, $widget) { 
                        //return $model->expenditureObject->object_code;
                        return $model->osdv->uacs ? $model->osdv->uacs->expenditureobject->object_code : '-';
                    },
                ],
                [   
                    'attribute'=>'gross_amount',
                    'header' => 'GROSS<br/>AMOUNT',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: right; padding-right: 25px; vertical-align: middle;'],
                    'format' => ['decimal',2],
                    'width'=>'150px',
                    'value'=>function ($model, $key, $index, $widget) {
                        return $model->osdv->getGrossamount();
                    },
                    'pageSummary' => true,
                    'pageSummaryFunc' => GridView::F_AVG,
                    'footer' => true
                ],
                [   
                    'attribute'=>'gross_amount',
                    'header' => 'WITHHOLDING<br/>TAX',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: right; padding-right: 25px; vertical-align: middle;'],
                    'format' => ['decimal',2],
                    'width'=>'150px',
                    'value'=>function ($model, $key, $index, $widget) {
                        if($model->creditor_id == 245){
                            $tax = Accounttransaction::find()->where(['request_id' => $model->osdv_id, 'account_id' => 31, 'debitcreditflag' => 2])->orderBy(['account_transaction_id' => SORT_DESC])->one();
                            
                            return $tax->amount;
                        }
                        else
                            return $model->osdv->getTax();
                    },
                    'pageSummary' => true,
                    'pageSummaryFunc' => GridView::F_AVG,
                    'footer' => true
                ],
                [   
                    'attribute'=>'gross_amount',
                    'header' => 'NET<br/>AMOUNT',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: right; padding-right: 25px; vertical-align: middle;'],
                    'format' => ['decimal',2],
                    'width'=>'150px',
                    'value'=>function ($model, $key, $index, $widget) {
                        return $model->osdv->getNetamount();
                        //return $model->amount;
                    },
                    'pageSummary' => true,
                    'pageSummaryFunc' => GridView::F_AVG,
                    'footer' => true
                ],
                
                [
                    'class' => 'kartik\grid\CheckboxColumn',
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'contentOptions' => ['style' => 'disabled'],
                    //'pageSummary' => '<small>(amounts in $)</small>',
                    //'pageSummaryOptions' => ['colspan' => 3, 'data-colspan-dir' => 'rtl']
                    'pageSummary' => false,
                    'footer' => true
                ],
                [   
                    'attribute'=>'gross_amount',
                    'header' => 'Print',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'format' => 'raw',
                    'width'=>'150px',
                    'value'=>function ($model, $key, $index, $widget) {
                        return Html::a('<i class="glyphicon glyphicon-print"></i>', Url::to(['/finance/request/printdv', 'id'=>$model->osdv->request->request_id]), ['target' => '_blank', 'data-pjax'=>0, 'class'=>'btn btn-primary']);
                    },
                    'pageSummary' => true,
                    'pageSummaryFunc' => GridView::F_AVG,
                    'footer' => true
                ],*/
            ];
    ?>
    <?= GridView::widget([
                'id' => 'lddap-ada-items',
                'dataProvider' => $payrollItemsDataProvider,
                //'filterModel' => $searchModel,
                'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => true, // pjax is set to always true for this demo
                // set left panel buttons
                'panel' => [
                    //'heading'=>'<h3 class="panel-title">CREDITORS</h3>',
                    'type'=>'primary',
                ],
                // set right toolbar buttons
                'toolbar' => 
                                [
                                    [
                                        'content'=>''
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
