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
/* @var $this yii\web\View */
/* @var $model common\models\cashier\Lddapada */

$this->title = $model->batch_number;
$this->params['breadcrumbs'][] = ['label' => 'LDDAP-ADA', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalCreditor',
    'size' => 'modal-lg',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalContainer',
    'size' => 'modal-sm',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalPreview',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalAda',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

?>
<div class="lddapada-view">

    <?php $attributes = [
            [
                'group'=>true,
                //'label'=>'<center>LDDAP-ADA</center>',
                'rowOptions'=>['class'=>'info'],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'batch_number',
                        'label' => 'Department',
                        'value'=>'Department of Science and Technology IX',
                        'valueColOptions'=>['style'=>'width:30%'],
                    ],
                    [
                        'attribute'=>'batch_number',
                        'format'=>'raw',
                        'value'=>'<kbd>'.$model->batch_number.'</kbd>',
                        'valueColOptions'=>['style'=>'width:30%; font-size:18px; font-weight: bold;'],
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'batch_number',
                        'value' => '',
                        'valueColOptions'=>['style'=>'width:30%'],
                        'label'=>'Entity Name',
                    ],
                    [
                        'attribute'=>'batch_date',
                        'value'=>date('m/d/Y', strtotime($model->batch_date)),
                        'valueColOptions'=>['style'=>'width:30%'],
                        'label'=>'Date',
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'batch_number',
                        'value' => '',
                        'valueColOptions'=>['style'=>'width:30%'],
                        'label'=>'Operating Unit',
                    ],
                    [
                        'attribute'=>'batch_number',
                        'value' => Lddapada::FUND_CLUSTER,
                        'valueColOptions'=>['style'=>'width:30%'],
                        'label'=>'Fund Cluster',
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'batch_number',
                        'value' => 'LBP Centro 2195-9000-54',
                        'valueColOptions'=>['style'=>'width:30%'],
                        'label'=>'MDS-GSB BRANCH / MDS SUB ACCOUNT NO.',
                        'inputContainer' => ['class'=>'col-sm-6'],
                    ],
                    [
                        'attribute'=>'batch_number',
                        'value' => '',
                        'valueColOptions'=>['style'=>'width:30%'],
                        'label'=>'',
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
                'heading'=>'<center>LIST OF DUE AND DEMANDABLE ACCOUNTS PAYABLE - ADVICE TO DEBIT ACCOUNTS<BR/>(LDDAP-ADA)</center>',
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
                //'name',
                [   
                    'attribute'=>'name',
                    'header' => 'NAME',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: left; padding-left: 10px; vertical-align: middle;'],
                    'format' => 'raw',
                    'width'=>'80px',
                    /*'value'=>function ($model, $key, $index, $widget) { 
                        return Requestattachment::generateCode($model->request_attachment_id);
                    },*/
                ],
                //'account_number',
                [   
                    'attribute'=>'account_number',
                    'header' => 'PREFERRED<br/>SERVICING BANK<br/>SAVINGS/CURRENT<br/>ACCOUNT NO.',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'format' => 'raw',
                    'width'=>'150px',
                    /*'value'=>function ($model, $key, $index, $widget) { 
                        return Requestattachment::generateCode($model->request_attachment_id);
                    },*/
                ],
                //'alobs_id',
                [   
                    'attribute'=>'alobs_id',
                    'header' => 'Obligation<br/>Request and<br/>Status No.',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'format' => 'raw',
                    'width'=>'150px',
                    'value'=>function ($model, $key, $index, $widget) { 
                        if($model->osdv->os)
                            return $model->osdv->os->os_number;
                        else
                            return '-';
                    },
                ],
                //'expenditure_object_id',
                [   
                    'attribute'=>'alobs_id',
                    'header' => 'ALLOTMENT<br/>CLASS per<br/>(UACS)',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'format' => 'raw',
                    'width'=>'150px',
                    'value'=>function ($model, $key, $index, $widget) { 
                        return $model->expenditureObject->object_code;
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
                    },
                    'pageSummary' => true,
                    'pageSummaryFunc' => GridView::F_AVG,
                    'footer' => true
                ],
                [   
                    'attribute'=>'check_number',
                    'header' => 'REMARKS',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'format' => 'raw',
                    'width'=>'150px',
                    /*'value'=>function ($model, $key, $index, $widget) { 
                        return Requestattachment::generateCode($model->request_attachment_id);
                    },*/
                    'pageSummary' => true,
                    'footer' => true
                    //Html::a('Disbursement Voucher  <i class="glyphicon glyphicon-print"></i>', Url::to(['request/printdv', 'id'=>$model->request_id]), ['target' => '_blank', 'data-pjax'=>0, 'class'=>'btn btn-primary'])
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
            ];
    ?>
    <?= GridView::widget([
                'id' => 'lddap-ada-items',
                'dataProvider' => $lddapadaItemsDataProvider,
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
                                        'content'=>
                                            /*Html::button('Add Items  <i class="glyphicon glyphicon-list"></i>', ['value' => Url::to(['lddapadaitem/additems', 'id'=>$model->lddapada_id]), 'title' => 'Items', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonAddItems']) . */
                                            Html::button('Assign Check No.  <i class="glyphicon glyphicon-check"></i>', ['value' => Url::to(['lddapada/assigncheck', 'id'=>$model->lddapada_id, 'typeId'=>$model->type_id]), 
                                                'title' => 'Check Number', 'class' => 'btn btn-warning', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonAssignCheckNo'
                                            ]) .
                                        
                                            Html::button('Assign ADA No.  <i class="glyphicon glyphicon-check"></i>', ['value' => Url::to(['lddapada/assignada', 'id'=>$model->lddapada_id, 'typeId'=>$model->type_id]), 
                                                'title' => 'ADA Number', 'class' => 'btn btn-warning', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonAssignAdaNo'
                                            ]) .
                                        
                                            Html::button('Add Creditors  <i class="glyphicon glyphicon-plus"></i>', ['value' => Url::to(['lddapadaitem/addcreditors', 'id'=>$model->lddapada_id, 'typeId'=>$model->type_id]), 'title' => 'Creditor', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonAddCreditors']) .
                                        
                                            Html::button('Save  <i class="glyphicon glyphicon-floppy-save"></i>', ['value' => Url::to(['lddapada/save', 'id'=>$model->lddapada_id]), 'title' => 'LDDAPADA', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;'.(($model->saved === Lddapada::CHANGED) ? '' : 'display: none;'), 'id'=>'buttonSave']) .
                                        
                                            /*Html::button('Print  <i class="glyphicon glyphicon-print"></i>', ['value' => Url::to(['lddapada/preview', 'id'=>$model->lddapada_id]), 'title' => 'LDDAPADA', 'class' => 'btn btn-info',  'data-pjax'=>0, 'style'=>'margin-right: 6px;'.(($model->saved === Lddapada::SAVED) ? '' : 'display: none;'), 'id'=>'buttonPrintPreview']) .*/
                                        
                                            Html::a('Print  <i class="glyphicon glyphicon-print"></i>', Url::to(['lddapada/print', 'id'=>$model->lddapada_id]), ['target' => '_blank', 'data-pjax'=>0, 'class'=>'btn btn-primary'])
                                        
                                            //$EnablePrint="<a href='/reports/preview?url=/lab/request/print-request?id=".$model->request_id."' class='btn btn-primary' style='margin-left: 5px'><i class='fa fa-print'></i> Print Request</a>";
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

<script>
$(document).ready(function(){
    $(".kv-row-checkbox").each(function(){
        if($(this).parent().prev().html().length != 0)
            $(this).prop("disabled",true);
    });
});
</script>