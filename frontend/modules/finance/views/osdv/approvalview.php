<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

use kartik\detail\DetailView;
use kartik\editable\Editable;
use kartik\grid\GridView;

use yii\bootstrap\Modal;
use common\models\cashier\Creditor;
use common\models\finance\Accounttransaction;
use common\models\finance\Dv;
use common\models\finance\Os;
use common\models\finance\Request;
use common\models\finance\Requestpayroll;
use common\models\finance\Requestattachment;
use common\models\finance\Requesttype;

/* @var $this yii\web\View */
/* @var $model common\models\finance\Request */

$this->title = $model->request->request_number;
$this->params['breadcrumbs'][] = ['label' => 'Request', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalContainer',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

/** Override for Payroll Items **/
/*$rq = Requestpayroll::findOne(22);
$status = 70;

$rq->osdv->status_id = $status;
$rq->osdv->save(false);

$rq->osdv->request->status_id = $status;
$rq->osdv->request->save(false);

echo $status.' : STATUS<br/>';
echo $rq->osdv->request->status_id.' : REQUEST<br/>';
echo $rq->osdv->status_id.' : OSDV<br/>';
echo $rq->status_id.' : PPAYROLL<br/>';*/
/** **/

//echo Os::generateOsNumber($model->request->obligation_type_id,$model->request->request_date);
?>
<div>
   
<div class="row">
    <div class="col-sm-8">
        
    <?php $attributes = [
            [
                'group'=>true,
                'label'=>'Details',
                //'rowOptions'=>['class'=>'table-success']
                'rowOptions'=>['class'=>'table-info']
            ],
            [
                'attribute'=>'request_id',
                'label'=>'Request Number',
                'inputContainer' => ['class'=>'col-sm-6'],
                'displayOnly'=>true,
                'format'=>'raw',
                'value' => $model->request->request_number . '  <span class="label label-success">'.$model->osdv_id.'</span>',
            ],
            [
                'attribute'=>'request_id',
                'label'=>'Request Type',
                'inputContainer' => ['class'=>'col-sm-6'],
                'value' => $model->request->requesttype->name,
                'type'=>DetailView::INPUT_SELECT2, 
                'widgetOptions'=>[
                    'data'=>ArrayHelper::map(Requesttype::find()->orderBy(['name'=>SORT_ASC])->all(),'request_type_id','name'),
                    'options' => ['placeholder' => 'Select Type'],
                    'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                ],
            ],
            [
                'attribute'=>'request_id',
                'label'=>'Payee',
                'inputContainer' => ['class'=>'col-sm-6'],
                'value' => $model->request->creditor->name,
                'type'=>DetailView::INPUT_SELECT2, 
                'widgetOptions'=>[
                    'data'=>ArrayHelper::map(Creditor::find()->orderBy(['name'=>SORT_ASC])->all(),'creditor_id','name'),
                    'options' => ['placeholder' => 'Select Payee'],
                    'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                ],
            ],
            [
                'attribute'=>'request_id',
                'label'=>'Particulars',
                'inputContainer' => ['class'=>'col-sm-6'],
                'value' => $model->request->particulars,
            ],

            [
                'attribute'=>'request_id',
                'label'=>'Amount (P)',
                'format'=>['decimal', 2],
                'inputContainer' => ['class'=>'col-sm-6'],
                'value' => $model->request->amount,
            ],
            [
                'group'=>true,
                'label'=>'Attachments',
                'rowOptions'=>['class'=>'table-success']
            ],
            [
                'attribute'=>'request_id',
                'label'=>'Links',
                //'format'=>['decimal', 2],
                //'inputContainer' => ['class'=>'col-sm-6'],
                //'value' => $model->request->amount,
            ],
        ];?>
    <?= DetailView::widget([
            'model' => $model,
            'mode'=>DetailView::MODE_VIEW,
            'container' => ['id'=>'kv-demo'],
            //'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
            //'buttons1' => ( (Yii::$app->user->identity->username == 'Admin') || $model->owner() ) ? '{update}' : '', //hides buttons on detail view
            'buttons1' => '',
            'attributes' => $attributes,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            //'formOptions' => ['action' => ['request/view', 'id' => $model->request_id]],
            'panel' => [
                //'type' => 'Primary', 
                'heading'=>'REQUEST DETAILS',
                //'heading'=>'OBLIGATION REQUEST - <span >'.$model->os->os_number,
                'type'=>DetailView::TYPE_PRIMARY,
                //'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
            ],
        ]); ?>
    </div>
    
    <div class="col-sm-4">
        <div class="panel panel-info">
        <div class="panel-heading"><b>Disbursement Status</b></div>
            <div class="panel-body">
                <div class="form-group" style="text-align: center; <?php echo (Yii::$app->user->can('access-finance-approval') ? ($model->status_id == Request::STATUS_APPROVED_FOR_DISBURSEMENT ? 'display: none;' : '') : 'display: none;') ?>">
                    <?php echo $model->payroll ? '<h4><span class="label label-info">See Payroll Items</span></h4>' : Html::button('APPROVE FINANCIAL REQUEST', ['value' => Url::to(['osdv/approve', 'id'=>$model->osdv_id]),     
                                                                        'title' => 'Approve for Disbursement', 'class' => 'btn btn-success '.($model->status_id == Request::STATUS_APPROVED_FOR_DISBURSEMENT ? 'disabled' : ''), 'id'=>'buttonGenerateDVNumber']);
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-4">
    <?php $attributes = [
            [
                'attribute'=>'type_id',
                'label'=>'Fund Source',
                'labelColOptions'=>['style'=>'width:35%; text-align: right;'],
                'inputContainer' => ['class'=>'col-sm-2'],
                'value' => $model->type->name,
            ],
            [
                'attribute'=>'expenditure_class_id',
                'label'=>'Expenditure Class',
                'inputContainer' => ['class'=>'col-sm-2'],
                'value' => $model->expenditureClass->name,
            ],    
            [
                'attribute'=>'request_id',
                'label'=>'OS Number',
                'visible' => ($model->type_id == 1) ? true : false,
                'inputContainer' => ['class'=>'col-sm-2'],
                'format' => 'raw',
                'displayOnly'=>true, //$model->os 
                'value' =>  
                    ($model->os ? '<h4><span class="label label-success">'.$model->os->os_number.'</span></h4>' : 
                    '<h4><span class="label label-warning">'.Os::generateOsNumber($model->expenditure_class_id, date("Y-m-d H:i:s")).'</span></h4>'.Html::button('Generate', ['value' => Yii::$app->user->can('access-finance-generateosnumber') ? Url::to(['osdv/generateosnumber', 'id'=>$model->osdv_id]) : Url::to(['osdv/notallowed', 'id'=>$model->osdv_id]),     
                                                                        'title' => 'Generate OS', 'class' => 'btn btn-md btn-success '
                                                                        .(Yii::$app->user->can('access-finance-generateosnumber') ? '': 'disabled'),
                                                                   'id'=>'buttonGenerateOSNumber'])) .
                    
                    Html::button('Obligate', ['value' => Url::to(['osdv/obligate', 'id'=>$model->osdv_id]),     
                                                                'title' => 'Allotment', 'class' => 'btn btn-info '.($model->status_id >= Request::STATUS_ALLOTTED ? 'disabled' : ''), 'style'=>'margin-right: 6px; '.(Yii::$app->user->can('access-finance-obligate') ? ($model->status_id >= Request::STATUS_ALLOTTED ? 'display: none;' : '') : 'display: none;'), 'id'=>'buttonObligate']),
            ],
            [
                'attribute'=>'request_id',
                'label'=>'DV Number',
                'inputContainer' => ['class'=>'col-sm-2'],
                'format' => 'raw',
                'displayOnly'=>true, //$model->os 
                'value' =>  
                    ($model->dv ? '<h4><span class="label label-success">'.$model->dv->dv_number.'</span></h4>' : 
                    '<h4><span class="label label-warning">'.Dv::generateDvNumber($model->expenditure_class_id, date("Y-m-d H:i:s")).'</span></h4>'.Html::button('Generate', ['value' => Yii::$app->user->can('access-finance-generatedvnumber') ? Url::to(['osdv/generatedvnumber', 'id'=>$model->osdv_id]) : Url::to(['osdv/notallowed', 'id'=>$model->osdv_id]),
                                                                        'title' => 'Generate DV', 'class' => 'btn btn-md btn-success '
                                                                        .(Yii::$app->user->can('access-finance-generatedvnumber') ? '' : 'disabled'),
                                                                   'id'=>'buttonGenerateDVNumber'])) .
                    
                    Html::button('Certify Funds Available', ['value' => Url::to(['osdv/certifycashavailable', 'id'=>$model->osdv_id]),     
                                                                'title' => 'Allotment', 'class' => 'btn btn-info '.($model->status_id >= Request::STATUS_CHARGED ? 'disabled' : ''), 'style'=>'margin-right: 6px; '.(Yii::$app->user->can('access-finance-certifycashavailable') ? ($model->status_id >= Request::STATUS_CHARGED ? 'display: none;' : '') : 'display: none;'), 'id'=>'buttonCertifyfundsavailable']),
            ],


        ];?>
    <?= DetailView::widget([
            'model' => $model,
            'mode'=>DetailView::MODE_VIEW,
            'container' => ['id'=>'kv-demo'],
            //'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
            
            //'buttons1' => ( (Yii::$app->user->identity->username == 'Admin') || $model->owner() ) ? '{update}' : '', //hides buttons on detail view
            'buttons1' => '',
            'attributes' => $attributes,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            'formOptions' => ['action' => ['request/view', 'id' => $model->request_id]],
            'panel' => [
                //'type' => 'Primary', 
                'heading'=>'OBLIGATION / DISBURSEMENT DETAILS',
                //'heading'=>'OBLIGATION REQUEST - <span >'.$model->os->os_number,
                'type'=>DetailView::TYPE_PRIMARY,
                //'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
            ],
        ]); ?>
    </div>
    
    
</div>
    
    
    <div class="obligation" style='<?php echo ($model->type_id == 1) ? "" : "display: none;" ?>' >
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
                    'attribute'=>'expenditure_class_id',
                    'header' => 'Expenditure Class',
                    'headerOptions' => ['style' => 'padding-left: 25px;'],
                    'contentOptions' => ['style' => 'padding-left: 25px; vertical-align: middle;'],
                    'width'=>'200px',
                    'value'=>function ($model, $key, $index, $widget) { 
                        //return $model->expenditure_class_id;
                        return $model->expenditureclass->name;
                    },
                ],
                [
                    'attribute'=>'expenditure_object_id',
                    'header' => 'Expenditure Object',
                    'headerOptions' => ['style' => 'padding-left: 25px;'],
                    'contentOptions' => ['style' => 'padding-left: 25px; vertical-align: middle;'],
                    'width'=>'200px',
                    'value'=>function ($model, $key, $index, $widget) { 
                        //return $model->expenditure_object_id;
                        return $model->expenditureobject->name;
                    },
                ],
                [
                    'attribute'=>'expenditure_object_id',
                    'header' => 'UACS Code',
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => 'padding-left: 25px; vertical-align: middle; text-align: center;'],
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                        //return $model->expenditure_object_id;
                        return $model->expenditureobject->object_code;
                    },
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'amount',
                    'header'=>'Amount',
                    'width'=>'350px',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal($model->amount);
                        },
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->osdv_id],
                            'placement'=>'left',
                            //'disabled'=>!Yii::$app->user->can('access-finance-approval'),
                            'disabled'=>true,
                            'name'=>'amount',
                            'asPopover' => true,
                            'value' => $model->amount,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/finance/osallotment/updateamount']], // point to the new action
                        ];
                    },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'padding-right: 20px;'],
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
            ];
    ?>
        
        <?= GridView::widget([
                'id' => 'request-obligation',
                'dataProvider' => $allotmentsDataProvider,
                //'filterModel' => $searchModel,
                'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => true, // pjax is set to always true for this demo
                // set left panel buttons
                /*'panel' => [
                    'heading'=>'<h3 class="panel-title">Attachments</h3>',
                    'type'=>'primary',
                ],*/    
                'panel' => [
                    'heading' => '<h3 class="panel-title">OBLIGATION</h3>',
                    'type' => GridView::TYPE_INFO,
                    'before'=>'',
                    'after'=>false,
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
    
    <?php if($model->payroll == 1) { ?>
    <?php
    $gridColumnsPayroll = [
            [
                'class' => 'kartik\grid\SerialColumn',
                'contentOptions' => ['class' => 'kartik-sheet-style'],
                'width' => '10px',
                'header' => '',
                'headerOptions' => ['style' => 'text-align: center; width: 10px;'],
                'pageSummary' => '',  
            ],
            /*[   
                'attribute'=>'creditor_id',
                'header' => 'Name',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: left; vertical-align: middle;'],
                'format' => 'raw',
                'width'=>'200px',
                'value'=> function ($model, $key, $index, $widget) { 
                    return $model->creditor->name;
                },
                
            ],*/
            [
                'attribute'=>'particulars',
                'header'=>'Particulars',
                'width'=>'650px',
                'value'=>function ($model, $key, $index, $widget) { 
                        return '<span style="text-align: left; font-weight: bold; float: left; color: black;">'.$model->creditor->name.'</span><br/><p style="text-align: left; float: left;">'.$model->particulars.'</p>';
                    },
                'format'=>'raw',
                'headerOptions' => ['style' => 'text-align: center'],
                'contentOptions' => [
                    'style'=>'max-width:150px; overflow: auto; white-space: normal; word-wrap: break-word;'
                ],
                'hAlign'=>'left',
                'vAlign'=>'left',
                'width'=>'800px',
                'pageSummary' => 'TOTAL', 
                'pageSummaryOptions' => ['style' => 'text-align: left;'],
            ],
            /*[
                'class'=>'kartik\grid\EditableColumn',
                'attribute'=>'particulars',
                'header'=>'Particulars',
                'refreshGrid'=>true,
                'width'=>'650px',
                'contentOptions' => [
                    'style'=>'max-width:150px; overflow: auto; white-space: normal; word-wrap: break-word;'
                ],
                'value'=>function ($model, $key, $index, $widget) { 
                        return '<span style="text-align: left; font-weight: bold; float: left; color: black;">'.$model->creditor->name.'</span><br/><p style="text-align: left; float: left;">'.$model->particulars.'</p>';
                    },
                'format'=>'raw',
                'editableOptions'=> function ($model , $key , $index) {
                    return [
                        'options' => ['id' => $index . '_' . $model->request_payroll_id],
                        'contentOptions' => ['style' => 'padding-right: 20px; text-align: left;'],
                        'placement'=>'bottom',
                        //'disabled'=>!Yii::$app->user->can('access-finance-disbursement'),
                        'name'=>'amount',
                        'asPopover' => true,
                        'value' => $model->particulars,
                        'inputType' => \kartik\editable\Editable::INPUT_TEXTAREA,
                        'size'=>'lg',
                        'options' => ['class'=>'form-control', 'rows'=>5, 'placeholder'=>'Enter particulars...'],
                        'formOptions'=>['action' => ['/finance/requestpayroll/updateamount']], // point to the new action
                    ];
                },
                'pageSummary' => 'TOTAL', 
                'pageSummaryOptions' => ['style' => 'text-align: right; padding-right:30px;'],
            ],*/
            [
                'attribute'=>'amount',
                'header'=>'Amount',
                'width'=>'350px',
                'format'=>['decimal',2],
                //'readonly' => !$isMember,
                'value'=>function ($model, $key, $index, $widget) { 
                        return $model->amount;
                    },
                'headerOptions' => ['style' => 'text-align: center'],
                'contentOptions' => ['style' => 'padding-right: 20px; vertical-align: middle; '],
                'hAlign'=>'right',
                'vAlign'=>'left',
                'width'=>'250px',
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
                'pageSummaryOptions' => ['style' => 'text-align: right; padding-right: 25px;'],
            ],
            [   
                'attribute'=>'request_id',
                'header' => 'DV Number',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle; font-weight: bold;'],
                'format' => 'raw',
                'width'=>'150px',
                'value'=> function ($model, $key, $index, $widget) { 
                    return isset($model->dv) ? $model->dv->dv_number : '';
                },
                'pageSummary' => false,
            ],
            [   
                'attribute'=>'request_id',
                'header' => 'Action',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle; font-weight: bold;'],
                'format' => 'raw',
                'width'=>'150px',
                'value'=>function ($model, $key, $index, $widget){
                    return ($model->status_id == Request::STATUS_APPROVED_FOR_DISBURSEMENT) ? '<span class="label label-info">APPROVED</span>' :
                    Html::button('APPROVE', ['value' => Url::to(['osdv/approvepayroll', 'id'=>$model->request_payroll_id]),     
                                                                'title' => 'Allotment', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px; '.(Yii::$app->user->can('access-finance-approval') ? ($model->status_id >= Request::STATUS_CHARGED ? '' : 'display: none;') : 'display: none;'), 'id'=>'buttonApprovepayroll']);
                },
                'pageSummary' => false,
                //Request::STATUS_CHARGED
            ],
        ];
?>
    
       <?= GridView::widget([
            'id' => 'payroll-items',
            'dataProvider' => $payrollDataprovider,
            //'filterModel' => $searchModel,
            'showFooter' => true,
            'showPageSummary' => true,
            'columns' => $gridColumnsPayroll, // check the configuration for grid columns by clicking button above
            
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            // set left panel buttons
            /*'panel' => [
                'heading'=>'<h3 class="panel-title">Attachments</h3>',
                'type'=>'primary',
            ],*/    
            'panel' => [
                'heading' => '<h3 class="panel-title">PAYROLL ITEMS</h3>',
                'type' => GridView::TYPE_SUCCESS,
                //'before'=> Html::button('Add Creditors', ['value' => Url::to(['request/payrollitems', 'id'=>$model->request_id]), 'title' => 'Submit', 'class' =>'btn btn-success', 'style'=>'margin-right: 6px;'.((($model->status_id < Request::STATUS_SUBMITTED)) ? ($model->attachments ? '' : 'display: none;') : 'display: none;'), 'id'=>'buttonPayrollitems']) ,
                'before'=> '',  
                'after'=>false,
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
            'itemLabelPlural' => 'items',
        ]);

    
    ?>
        
    <?php } ?>
    
    <?php
        $gridColumns = [
                [
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '100px',
                    'header' => '',
                    //'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    //'mergeHeader' => true,
                ],
                [
                    'attribute'=>'account_id',
                    'header' => 'Account Title',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: left; padding-left: 25px; vertical-align: middle;'],
                    'width'=>'750px',
                    'value'=>function ($model, $key, $index, $widget) { 
                        return $model->account->title;
                    },
                ],
                [
                    'attribute'=>'account_id',
                    'header' => 'UACS',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'width'=>'550px',
                    'value'=>function ($model, $key, $index, $widget) { 
                        return $model->account->object_code;
                    },
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'amount',
                    'header'=>'Debit',
                    //'width'=>'350px',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            //return $fmt->asDecimal($model->amount);
                            return ($model->transaction_type == Accounttransaction::DEBIT) ? $fmt->asDecimal($model->amount) : '';
                        },
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_10_' . $model->account_transaction_id],
                            'placement'=>'left',
                            'disabled'=>!Yii::$app->user->can('access-finance-obligation'),
                            //'disabled'=>true,
                            'name'=>'amount',
                            'asPopover' => true,
                            'value' => $model->amount,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/finance/accounttransaction/updateamount']], // point to the new action
                        ];
                    },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'padding-right: 20px;'],
                    'hAlign'=>'right',
                    //'vAlign'=>'middle',
                    'width'=>'250px',
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'amount',
                    'header'=>'Credit',
                    //'width'=>'350px',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            //return $fmt->asDecimal($model->amount);
                            return ($model->transaction_type == Accounttransaction::CREDIT) ? $fmt->asDecimal($model->amount) : '';
                        },
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_20_' . $model->account_transaction_id],
                            'placement'=>'left',
                            'disabled'=>!Yii::$app->user->can('access-finance-disbursement'),
                            //'disabled'=>true,
                            'name'=>'amount',
                            'asPopover' => true,
                            'value' => $model->amount,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/finance/accounttransaction/updateamount']], // point to the new action
                        ];
                    },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'padding-right: 20px;'],
                    'hAlign'=>'right',
                    //'vAlign'=>'middle',
                    'width'=>'250px',
                ],
            ];
    ?>
        
        <?= GridView::widget([
                'id' => 'account-transactions',
                'dataProvider' => $accountTransactionsDataProvider,
                //'filterModel' => $searchModel,
                'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => true, // pjax is set to always true for this demo
                // set left panel buttons
                /*'panel' => [
                    'heading'=>'<h3 class="panel-title">Attachments</h3>',
                    'type'=>'primary',
                ],*/    
                'panel' => [
                    'heading' => '<h3 class="panel-title">DISBURSEMENT</h3>',
                    'type' => GridView::TYPE_WARNING,
                    'before'=>'',/*Html::button('Add', ['value' => Url::to(['accounttransaction/additems', 'id'=>$model->osdv_id, 'year'=>$year]),     
                                                                'title' => 'Account Transactions', 'class' => 'btn btn-success'.($model->status_id == Request::STATUS_CERTIFIED_FUNDS_AVAILABLE ? 'disabled' : ''), 'style'=>'margin-right: 6px;'.(Yii::$app->user->can('access-finance-disbursement') ? '' : 'display: none;'), 'id'=>'buttonAddAccounttransaction']) ,.
                              Html::button('Certify Cash Available', ['value' => Url::to(['osdv/certifycashavailable', 'id'=>$model->osdv_id]),     
                                                                'title' => 'Allotment', 'class' => 'btn btn-info '.($model->status_id == Request::STATUS_CERTIFIED_FUNDS_AVAILABLE ? 'disabled' : ''), 'style'=>'margin-right: 6px; '.(Yii::$app->user->can('access-finance-certifycashavailable') ? ($model->status_id == Request::STATUS_CERTIFIED_FUNDS_AVAILABLE ? 'display: none;' : '') : 'display: none;'), 'id'=>'buttonObligate']),*/
                    'after'=>false,
                ],
                // set right toolbar buttons
                /*'toolbar' => 
                                [
                                    [
                                        'content'=>
                                            Html::button('Generate Attachments  <i class="glyphicon glyphicon-list"></i>', ['value' => Url::to(['request/generateattachments', 'id'=>$model->request_id]), 'title' => 'Attachment', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonGenerateAttachments'])
                                    ],
                                ],*/
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
            
            //'name',
            [
                'attribute'=>'attachment_id',
                'header' => 'Required Documents',
                'contentOptions' => ['style' => 'padding-left: 25px; vertical-align: middle;'],
                'width'=>'550px',
                'format' => 'raw',
                'value'=>function ($model, $key, $index, $widget) { 
                    $comments = 0;
                    $record_id = $model->request_attachment_id;
                    $component = 'Attachment';

                    return $model->attachment->name. ' ' . 
                        
                    //Html::button('', ['value' => Url::to(['request/comments', 'id'=>$model->request_id]), 'title' => 'comments', 'class' => 'glyphicon glyphicon-comment', 'id'=>'buttonComment']) .
                        
                    Html::a('<i class="fa fa-lg fa-comment"></i> '.$comments.' comments',[''], ['class' => 'btn btn-black', 'title' => 'Comments', 'onClick'=>               "{
                            //alert($(this).attr('title'));
                            //loadModal('comments?record_id=$record_id&component=$component'); 
                            loadModal('/system/comment/create?record_id=$record_id&component=$component'); 
                            return false;
                    
                        }"])

                    ;
                },
            ],
            [   
                'attribute'=>'filename',
                'header' => 'Attachments',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'format' => 'raw',
                'width'=>'80px',
                'value'=>function ($model, $key, $index, $widget) { 
                    $btnCss = [];
                    $status = Requestattachment::hasAttachment($model->request_attachment_id);
                    
                    switch($status){
                        case 0:
                                $btnCss = 'btn btn-danger';
                                break;
                        case 1:
                                if($model->status_id)
                                    $btnCss = 'btn btn-success';
                                else
                                    $btnCss = 'btn btn-warning';
                                break;
                    }
                    
                    return Html::button('<i class="glyphicon glyphicon-file"></i> View', ['value' => Url::to(['request/uploadattachment', 'id'=>$model->request_attachment_id]), 'title' => Yii::t('app', "Attachment"), 'class' => $btnCss, 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonUploadAttachments']);
                },
            ],
            [   
                'attribute'=>'filename',
                'header' => 'For Approval',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'format' => 'raw',
                'width'=>'80px',
                'value'=>function ($model, $key, $index, $widget) { 
                    $btnCss = [];
                    $status = Requestattachment::hasAttachment($model->request_attachment_id);
                    
                    switch($status){
                        case 0:
                            $btnCss = 'btn btn-danger';
                            break;
                        case 1:
                            if($model->status_id)
                                $btnCss = 'btn btn-success';
                            else
                                $btnCss = 'btn btn-warning';
                            break;
                    }
                    
                    return Html::button('<i class="glyphicon glyphicon-file"></i> View', ['value' => Url::to(['request/uploadattachmenttest', 'id'=>$model->request_attachment_id]), 'title' => Yii::t('app', "Attachment"), 'class' => $btnCss, 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonUploadAttachmentstest']);
                },
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute'=>'status_id',
                'header' => 'Verified',
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'width'=>'60px',
                //'value'=>function ($model, $key, $index, $widget) { 
                    //return $model->status_id;
                //},
            ],
        ];
?>
    
    <?= GridView::widget([
            'id' => 'request-attachments',
            'dataProvider' => $attachmentsDataProvider,
            //'filterModel' => $searchModel,
            'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            // set left panel buttons
            /*'panel' => [
                'heading'=>'<h3 class="panel-title">Attachments</h3>',
                'type'=>'primary',
            ],*/    
            'panel' => [
                'heading' => '<h3 class="panel-title">Attachments</h3>',
                'type' => GridView::TYPE_PRIMARY,
                'before'=> '', 
                
                'after'=>false,
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
<br><br>
<?php 
//return Yii::$app->controller->renderPartial('_request_payroll', ['dataProvider' => $dataProvider, 'id'=>$id]);
//echo Yii::$app->controller->renderPartial('_dummy');

?>