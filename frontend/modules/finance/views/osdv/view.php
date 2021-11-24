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
use common\models\finance\Obligationtype;
use common\models\finance\Os;
use common\models\finance\Request;
use common\models\finance\Requestattachment;
use common\models\finance\Requesttype;
use common\models\finance\Taxcategory;
use common\models\procurement\Expenditureclass;
use common\models\system\Comment;

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

//echo $model->status_id.'<br/>';
//echo Os::generateOsNumber($model->request->obligation_type_id,$model->request->request_date);
?>
<!--pre>
<?php //print_r(yii::$app->user->profile);?>
</pre-->
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
                'displayOnly'=>true,
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
                'displayOnly'=>true,
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
                'displayOnly'=>true,
                'value' => $model->request->particulars,
            ],

            [
                'attribute'=>'grossamount',
                'label'=>'Amount (P)',
                'format'=>['decimal', 2],
                'inputContainer' => ['class'=>'col-sm-6'],
                'value' => $model->grossamount,
            ],
            [
                'group'=>true,
                'label'=>'Status',
                'rowOptions'=>['class'=>'table-success']
            ],
            [
                'attribute'=>'remarks',
                'label'=>'Remarks',
                'format'=>'raw',
                'inputContainer' => ['class'=>'col-sm-6'],
                'value' => ($model->request->cancelled || $model->cancelled) ? '<span class="label label-danger">CANCELLED</span>' : $model->remarks,
            ],
            /*[
                'attribute'=>'request_id',
                'label'=>'Amount (P)',
                'format'=>['decimal', 2],
                'inputContainer' => ['class'=>'col-sm-6'],
                'value' => $model->request->amount,
            ],*/
        ];?>
    
    
    
    <?= DetailView::widget([
            'model' => $model,
            'mode'=>DetailView::MODE_VIEW,
            'container' => ['id'=>'kv-demo'],
            //'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
            'buttons1' => ( (Yii::$app->user->identity->username == 'Admin') || Yii::$app->user->can('access-finance-disbursement') ) ? '{update}' : '', //hides buttons on detail view
            //'buttons1' => '',
            'attributes' => $attributes,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            'formOptions' => ['action' => ['osdv/view', 'id' => $model->osdv_id]],
            'panel' => [
                //'type' => 'Primary', 
                'heading'=>'REQUEST DETAILS',
                //'heading'=>'OBLIGATION REQUEST - <span >'.$model->os->os_number,
                'type'=>DetailView::TYPE_PRIMARY,
                //'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
            ],
        ]); ?>
        
    
    </div>
    <?php if(!$model->cancelled){ ?>
    <div class="col-sm-4">
    <?php $attributes = [
            [
                'attribute'=>'type_id',
                'label'=>'Fund Source',
                'labelColOptions'=>['style'=>'width:35%; text-align: right;'],
                'inputContainer' => ['class'=>'col-md-12'],
                'value' => $model->type->name,
                'type'=>DetailView::INPUT_SELECT2, 
                'widgetOptions'=>[
                    'data' => ArrayHelper::map(Obligationtype::find()->orderBy(['type_id'=>SORT_ASC])->all(),'type_id','name'),
                    'options' => ['placeholder' => 'Select Expenditure Class'],
                    'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                ],
            ],
            [
                'attribute'=>'expenditure_class_id',
                'label'=>'Expenditure Class',
                'inputContainer' => ['class'=>'col-md-12'],
                'value' => $model->expenditure_class_id ? $model->expenditureClass->name : '-',
                'type'=>DetailView::INPUT_SELECT2, 
                'widgetOptions'=>[
                    //'data'=>ArrayHelper::map(Creditor::find()->orderBy(['name'=>SORT_ASC])->all(),'creditor_id','name'),
                    'data' => ArrayHelper::map(Expenditureclass::find()->orderBy(['expenditure_class_id'=>SORT_ASC])->all(),'expenditure_class_id','name'),
                    'options' => ['placeholder' => 'Select Expenditure Class'],
                    'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                ],
            ],    
            [
                'attribute'=>'request_id',
                'label'=>'OS Number',
                'inputContainer' => ['class'=>'col-sm-2'],
                'format' => 'raw',
                'visible' => ($model->type_id == 1) ? true : false,
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
                    $model->payroll ? "" : (
                
                    ($model->dv ? '<h4><span class="label label-success">'.$model->dv->dv_number.'</span></h4>' : 
                    '<h4><span class="label label-warning">'.Dv::generateDvNumber($model->request->obligation_type_id, $model->expenditure_class_id, date("Y-m-d H:i:s")).'</span></h4>'.Html::button('Generate', ['value' => Yii::$app->user->can('access-finance-generatedvnumber') ? Url::to(['osdv/generatedvnumber', 'id'=>$model->osdv_id]) : Url::to(['osdv/notallowed', 'id'=>$model->osdv_id]),
                                                                        'title' => 'Generate DV', 'class' => 'btn btn-md btn-success '
                                                                        .(Yii::$app->user->can('access-finance-generatedvnumber') ? '' : 'disabled'),
                                                                   'id'=>'buttonGenerateDVNumber'])).
                    
                    Html::button('Certify Funds Available', ['value' => Url::to(['osdv/certifycashavailable', 'id'=>$model->osdv_id]),     
                                                                'title' => 'Allotment', 'class' => 'btn btn-info '.($model->status_id >= Request::STATUS_CHARGED ? 'disabled' : ''), 'style'=>'margin-right: 6px; '.(Yii::$app->user->can('access-finance-certifycashavailable') ? ($model->status_id >= Request::STATUS_CHARGED ? 'display: none;' : '') : 'display: none;'), 'id'=>'buttonCertifyfundsavailable'])
                    ),
            ],


        ];?>
    <?= DetailView::widget([
            'model' => $model,
            'mode'=>DetailView::MODE_VIEW,
            'container' => ['id'=>'os-details'],
            //'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
            
            //'buttons1' => ( (Yii::$app->user->identity->username == 'Admin') || (Yii::$app->user->can('access-finance-generateosnumber') ) ) ? '{update}' : '', //hides buttons on detail view
            'buttons1' => '{update}',
            'attributes' => $attributes,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            'formOptions' => ['action' => ['osdv/view', 'id' => $model->osdv_id]],
            'panel' => [
                //'type' => 'Primary', 
                'heading'=>'OBLIGATION / DISBURSEMENT DETAILS',
                'type'=>DetailView::TYPE_PRIMARY,
                //'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
            ],
        ]); ?>
    </div>
    <?php } ?>
    
    <div class="col-sm-4" style="display: none; ">
        <div class="panel panel-info">
        <div class="panel-heading"><b>Disbursement Details</b></div>
            <div class="panel-body">
                <div class="form-group" style="text-align: center; <?php echo (Yii::$app->user->can('access-finance-approval') ? ($model->status_id == Request::STATUS_APPROVED_FOR_DISBURSEMENT ? 'display: none;' : '') : 'display: none;') ?>">
                    <?php echo Html::button('Generate DV Number', ['value' => Url::to(['dv/generatedvnumber', 'id'=>$model->osdv_id]),     
                                                                        'title' => 'Approve for Disbursement', 'class' => 'btn btn-success '.($model->status_id == Request::STATUS_APPROVED_FOR_DISBURSEMENT ? 'disabled' : ''), 'id'=>'buttonGenerateDVNumber']);
                        //.($model->status_id == Request::STATUS_APPROVED_FOR_DISBURSEMENT ? 'disabled' : '')
                        //.(Yii::$app->user->can('access-finance-approval') ? ($model->status_id == Request::STATUS_APPROVED_FOR_DISBURSEMENT ? 'display: none;' : '') : 'display: none;')
                    ?>
                    
                    
                </div>
                <span class="badge badge-success">Approved</span>
                
            </div>
        </div>
    </div>
</div>
    
    
    <div class="obligation" style='<?php echo ($model->type_id == 1) ? "" : "display: none;" ?>'>
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
                    'attribute'=>'expenditure_object_id',
                    'header' => 'Expenditure Object',
                    'headerOptions' => ['style' => 'padding-left: 25px;'],
                    'contentOptions' => ['style' => 'padding-left: 25px; vertical-align: middle;     font-weight: bold;'],
                    'width'=>'200px',
                    'value'=>function ($model, $key, $index, $widget) { 
                        //return $model->expenditure_object_id;
                        return $model->expenditureobject->name;
                    },
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
                    'format'=>['decimal',2],
                    //'readonly' => !$isMember,
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->amount;
                        },
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->osdv_id],
                            'placement'=>'left',
                            'disabled'=>!Yii::$app->user->can('access-finance-obligation'),
                            //'disabled'=>true,
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
                    'pageSummary' => true,
                    'pageSummaryFunc' => GridView::F_SUM,
                    'pageSummaryOptions' => ['style' => 'text-align: right; padding-right: 25px;'],
                ],
            ];
    ?>
        
        <?= GridView::widget([
                'id' => 'request-obligation',
                'dataProvider' => $allotmentsDataProvider,
                //'filterModel' => $searchModel,
                'showPageSummary' => true,
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
                    'before'=>Html::button('Add', ['value' => Url::to(['osallotment/additems', 'id'=>$model->osdv_id, 'year'=>$year]),     
                                                                'title' => 'Allotment', 'class' => 'btn btn-success '.($model->status_id == Request::STATUS_ALLOTTED ? 'disabled' : ''), 'style'=>'margin-right: 6px; '.(Yii::$app->user->can('access-finance-obligation') ? ($model->status_id == Request::STATUS_ALLOTTED ? 'display: none;' : '') : 'display: none;'), 'id'=>'buttonAddAllotment']) /*. 
                              Html::button('Obligate', ['value' => Url::to(['osdv/obligate', 'id'=>$model->osdv_id]),     
                                                                'title' => 'Allotment', 'class' => 'btn btn-info '.($model->status_id == Request::STATUS_ALLOTTED ? 'disabled' : ''), 'style'=>'margin-right: 6px; '.(Yii::$app->user->can('access-finance-obligate') ? ($model->status_id == Request::STATUS_ALLOTTED ? 'display: none;' : '') : 'display: none;'), 'id'=>'buttonObligate'])*/,
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
    
    <?php //if( ($model->payroll == 1 && Yii::$app->user->can('access-finance-disbursement')) || (Yii::$app->user->identity->username == 'Admin')) { ?>
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
            ],
            [
                'class'=>'kartik\grid\EditableColumn',
                'attribute'=>'dv_accounts',
                'header'=>'DV Accounts',
                'refreshGrid'=>true,
                'value'=>function ($model, $key, $index, $widget) { 
                        $keys = explode(',',$model->dv_accounts);
                            $text = "";
                            for($i=0; $i<count($keys); $i++){
                                $account = Accounttransaction::findOne($keys[$i]);
                                if($account)
                                    $text .= ( (count($keys[$i]) - $i) > 1) ? $account->account->title : $account->account->title.',<br/>';
                            }
                            return $text;
                    },
                'format'=>'raw',
                'editableOptions'=> function ($model , $key , $index) {
                    return [
                        'options' => ['id' => $index . '_' . $model->request_payroll_id],
                        'placement'=>'right',
                        //'disabled'=>!Yii::$app->user->can('access-finance-disbursement'),
                        'name'=>'amount',
                        'asPopover' => true,
                        'value' => function ($model) {
                            $keys = explode(',',$model->dv_accounts);
                            $text = "";
                            for($i=0; $i<count($keys); $i++){
                                if($account)
                                    $text .= ( (count($keys[$i]) - $i) > 1) ? $account->account->title : $account->account->title.',<br/>';
                            }
                            return $text;
                        },
                        'format'=>'raw',
                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                        'data' => ArrayHelper::map(Accounttransaction::find()->where('request_id =:request_id',[':request_id'=>$model->osdv->osdv_id])->all(),'account_transaction_id','account.title'),
                        'size'=>'md',
                        'options' => ['multiple'=>true, 'class'=>'form-control', 'rows'=>10,],
                        'formOptions'=>['action' => ['/finance/requestpayroll/updatedvaccounts']], // point to the new action
                    ];
                },
                'headerOptions' => ['style' => 'text-align: center'],
                'contentOptions' => [
                    'style'=>'max-width:150px; overflow: auto; white-space: normal; word-wrap: break-word; text-align: left;'
                ],
                //'hAlign'=>'left',
                //'vAlign'=>'top',
                'width'=>'200px',
                'pageSummary' => 'TOTAL', 
                'pageSummaryOptions' => ['style' => 'text-align: left;'],
            ],
            [
                'class'=>'kartik\grid\EditableColumn',
                'attribute'=>'amount',
                'header'=>'Gross Amount',
                'width'=>'350px',
                'refreshGrid'=>true,
                'format'=>['decimal',2],
                //'readonly' => !$isMember,
                'value'=>function ($model, $key, $index, $widget) { 
                        return $model->amount;
                    },
                'editableOptions'=> function ($model , $key , $index) {
                    return [
                        'options' => ['id' => $index . '_' . $model->request_payroll_id],
                        'placement'=>'left',
                        'disabled'=>!Yii::$app->user->can('access-finance-disbursement'),
                        //'disabled'=>true,
                        'name'=>'amount',
                        'asPopover' => true,
                        'value' => $model->amount,
                        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                        'formOptions'=>['action' => ['/finance/requestpayroll/updateamount']], // point to the new action
                    ];
                },
                'headerOptions' => ['style' => 'text-align: center'],
                'contentOptions' => ['style' => 'padding-right: 20px;'],
                'hAlign'=>'right',
                'vAlign'=>'left',
                'width'=>'250px',
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
                'pageSummaryOptions' => ['style' => 'text-align: right; padding-right: 25px;'],
            ],
            [
                'class'=>'kartik\grid\EditableColumn',
                'attribute'=>'tax',
                'header'=>'Tax',
                'width'=>'350px',
                'refreshGrid'=>true,
                'format'=>['decimal',2],
                //'readonly' => !$isMember,
                'value'=>function ($model, $key, $index, $widget) { 
                        return $model->tax;
                    },
                'editableOptions'=> function ($model , $key , $index) {
                    return [
                        'options' => ['id' => $index . '-' . $model->request_payroll_id],
                        'placement'=>'left',
                        'disabled'=>!Yii::$app->user->can('access-finance-disbursement'),
                        //'disabled'=>true,
                        'name'=>'tax',
                        'asPopover' => true,
                        'value' => $model->tax,
                        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                        'formOptions'=>['action' => ['/finance/requestpayroll/updateamount']], // point to the new action
                    ];
                },
                'headerOptions' => ['style' => 'text-align: center'],
                'contentOptions' => ['style' => 'padding-right: 20px;'],
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
                /*'value'=> function ($model, $key, $index, $widget) { 
                    //return $model->dv->dv_number;
                    return isset($model->dv) ? $model->dv->dv_number : '';
                },*/
                'value'=>function ($model, $key, $index, $widget){
                    $label = ($model->status_id >= Request::STATUS_CHARGED ? '<h4><span class="label label-info">'.$model->dv->dv_number.'</span></h4>' : '<h4><span class="label label-success">'.($model->dv ? $model->dv->dv_number : "").'</span></h4>');
                    
                    return ($model->dv ? $label :                         
                    Html::button('Generate', ['value' => Yii::$app->user->can('access-finance-generatedvnumber') ? Url::to(['osdv/generatedvnumber', 'id'=>$model->osdv_id, 'request_payroll_id'=>$model->request_payroll_id]) : Url::to(['osdv/notallowed', 'id'=>$model->osdv_id]),
                                                                        'title' => 'Generate DV', 'class' => 'btn btn-md btn-success'
                                                                        .(Yii::$app->user->can('access-finance-generatedvnumber') ? '' : 'disabled'),
                                                                   'id'=>'buttonGenerateDVNumber']));
                },
                'pageSummary' => false,
            ],
            /*[   
                'attribute'=>'request_id',
                'header' => 'DV Number',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle; font-weight: bold;'],
                'format' => 'raw',
                'width'=>'150px',
                'value'=>function ($model, $key, $index, $widget){
                    return ($model->dv ? 
                    Html::button('Certify Funds Available', ['value' => Url::to(['osdv/certifycashavailable', 'id'=>$model->osdv_id, 'request_payroll_id'=>$model->request_payroll_id]),     
                                                                'title' => 'Allotment', 'class' => 'btn btn-info'.($model->status_id >= Request::STATUS_CHARGED ? 'disabled' : ''), 'style'=>'margin-right: 6px; '.(Yii::$app->user->can('access-finance-certifycashavailable') ? ($model->status_id >= Request::STATUS_CHARGED ? 'display: none;' : '') : 'display: none;'), 'id'=>'buttonCertifyfundsavailable']) : ''
                    );
                },
                'pageSummary' => false,
            ],*/
            [   
                    'attribute'=>'gross_amount',
                    'header' => 'Print',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'format' => 'raw',
                    'width'=>'150px',
                    'value'=>function ($model, $key, $index, $widget) {
                        return $model->request_payroll_id ? 
                        
                        Html::a('<i class="glyphicon glyphicon-print"></i>', Url::to(['/finance/request/printdvpayroll', 'id'=>$model->request_payroll_id]), ['target' => '_blank', 'data-pjax'=>0, 'class'=>'btn btn-primary']) 
                            : 
                        Html::a('<i class="glyphicon glyphicon-print"></i>', Url::to(['/finance/request/printdv', 'id'=>$model->osdv->request->request_id]), ['target' => '_blank', 'data-pjax'=>0, 'class'=>'btn btn-primary']);
                    },
                    'pageSummary' => true,
                    'pageSummaryFunc' => GridView::F_AVG,
                    'footer' => true
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
                'before'=> Html::button('Add Creditors', ['value' => Url::to(['osdv/payrollitems', 'id'=>$model->osdv_id]), 'title' => 'Submit', 'class' =>'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonPayrollitems']) ,
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
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: left; padding-left: 25px; vertical-align: middle; font-weight: bold;'],
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
                    'attribute'=>'debitcreditflag',
                    'header'=>'Entry Type',
                    //'width'=>'350px',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'value'=>function ($model, $key, $index, $widget) {
                            if($model->debitcreditflag == 1)
                                return 'DEBIT';
                            if($model->debitcreditflag == 2)
                                return 'CREDIT';
                        },
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_10_' . $model->account_transaction_id],
                            'contentOptions' => ['style' => 'text-align: center;'],
                            'placement'=>'left',
                            'disabled'=>!Yii::$app->user->can('access-finance-disbursement'),
                            'name'=>'amount',
                            'asPopover' => true,
                            'value'=>function ($model, $key, $index, $widget) {
                                if($model->debitcreditflag == 1)
                                    return 'DEBIT';
                                if($model->debitcreditflag == 2)
                                    return 'CREDIT';
                            },
                            'inputType' => Editable::INPUT_DROPDOWN_LIST,
                            'data'=>['1'=>'Debit', '2'=>'Credit'], // any list of values
                            'formOptions'=>['action' => ['/finance/accounttransaction/updateflag']], // point to the new action
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
                    'attribute'=>'taxable',
                    'header'=>'Taxable',
                    //'width'=>'350px',
                    'refreshGrid'=>true,
                    'value'=>function ($model, $key, $index, $widget) {
                        if($model->debitcreditflag == 2){
                            if($model->taxable == 0)
                                return 'NO';
                            if($model->taxable == 1)
                                return 'YES';
                        }else{
                            return 'NO';
                        }
                    },
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_30_' . $model->taxable],
                            'contentOptions' => ['style' => 'text-align: center;'],
                            'placement'=>'left',
                            'disabled'=>($model->debitcreditflag == 1),
                            'name'=>'district',
                            'asPopover' => true,
                            'value'=>function ($model, $key, $index, $widget) {
                                if($model->debitcreditflag == 2){
                                    if($model->taxable == 0)
                                        return 'NO';
                                    if($model->taxable == 1)
                                        return 'YES';
                                }else{
                                    return 'NO';
                                }
                            },
                            'inputType' => Editable::INPUT_DROPDOWN_LIST,
                            'data'=>['0'=>'NO', '1'=>'YES'],
                            'formOptions'=>['action' => ['/finance/accounttransaction/updatetaxreg']], // point to the new action
                        ];
                    },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'padding-right: 20px;'],
                    'hAlign'=>'right',
                    //'vAlign'=>'middle',
                    'width'=>'250px',
                ],
                
                /*[
                    'class' => 'kartik\grid\CheckboxColumn',
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'pageSummary' => '<small>(amounts in $)</small>',
                    'pageSummaryOptions' => ['colspan' => 3, 'data-colspan-dir' => 'rtl']
                ],*/
                /*[
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'amount',
                    'header'=>'Amount',
                    //'width'=>'350px',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'value' => function($model , $key , $index){
                                $fmt = Yii::$app->formatter;
                                $tax_amount = 0.00;
                                if($model->tax_registered)
                                    $taxable_amount = $model->amount / 1.12;
                                else
                                    $taxable_amount = $model->amount;

                                if($model->amount < 10000.00){
                                    $tax_amount = $taxable_amount * $model->rate1;
                                }else{
                                    $tax_amount = ($taxable_amount * $model->rate1) + ($taxable_amount * $model->rate2);
                                }
                                
                                return $fmt->asDecimal($model->amount - $tax_amount);
                            },
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_90_' . $model->account_transaction_id],
                            'placement'=>'left',
                            'disabled'=>!Yii::$app->user->can('access-finance-disbursement'),
                            //'disabled'=>true,
                            'name'=>'amount',
                            'asPopover' => true,
                            'value' => function($model , $key , $index){
                                $fmt = Yii::$app->formatter;
                                $tax_amount = 0.00;
                                if($model->tax_registered)
                                    $taxable_amount = $model->amount / 1.12;
                                else
                                    $taxable_amount = $model->amount;

                                if($model->amount < 10000.00){
                                    $tax_amount = $taxable_amount * $model->rate1;
                                }else{
                                    $tax_amount = ($taxable_amount * $model->rate1) + ($taxable_amount * $model->rate2);
                                }
                                
                                return $fmt->asDecimal($model->amount - $tax_amount);
                                //return ($model->amount - $tax_amount);
                            },
                            //'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                            'options' => [
                                'pluginOptions' => ['min' => 0, 'max' => 5000000]
                            ],
                            'formOptions'=>['action' => ['/finance/accounttransaction/updateamount']], // point to the new action
                        ];
                    },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'padding-right: 20px;'],
                    'hAlign'=>'right',
                    //'vAlign'=>'middle',
                    'width'=>'250px',
                    'pageSummary' => true
                ],*/
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'amount',
                    'header'=>'Amount',
                    'width'=>'350px',
                    'format'=>['decimal',2],
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'value'=>function ($model, $key, $index, $widget) { 
                            $tax_amount = 0.00;
                        
                            switch ($model->tax_category_id) {
                              case 1: //Goods (5% + 1%); check if 10k above; if below 10 check if supplier is tagged(amount divide by 1.12%)
                                    if($model->osdv->request->creditor->tagged || $model->tax_registered)
                                        $taxable_amount = round($model->amount / 1.12, 2);
                                    else
                                        $taxable_amount = $model->amount;

                                    if($model->osdv->request->creditor->tagged || $model->amount >= 10000.00){
                                        $tax1 = round($taxable_amount * $model->rate1, 2);
                                        $tax2 = round($taxable_amount * $model->rate2, 2);
                                        $tax_amount = $tax1 + $tax2;
                                    }else{
                                        $tax_amount = round($taxable_amount * $model->rate1, 2);
                                    }
                                break;

                              case 2: // Services  (5% + 2%); check if 10k above; if below 10 check if supplier is tagged(amount divide by 1.12%)
                                    if($model->osdv->request->creditor->tagged || $model->tax_registered)
                                        $taxable_amount = round($model->amount / 1.12, 2);
                                    else
                                        $taxable_amount = $model->amount;

                                    if($model->osdv->request->creditor->tagged || $model->amount >= 10000.00){
                                        $tax1 = round($taxable_amount * $model->rate1, 2);
                                        $tax2 = round($taxable_amount * $model->rate2, 2);
                                        $tax_amount = $tax1 + $tax2;
                                    }else{
                                        $tax_amount = round($taxable_amount * $model->rate1, 2);
                                    }
                                break;

                              case 3: //Rental  (5%)
                                    $tax_amount = round($model->amount * $model->rate1, 2);
                                break;

                              case 4: //Professional (10%)
                                    $tax_amount = round($model->amount * $model->rate1, 2);
                                break;
                                
                              case 5: //Computed
                                    if ($model->account_id == 31) {
                                        $tax_amount = 0;
                                    }else{
                                        $transaction = Accounttransaction::find()->where(['request_id' => $model->request_id, 'account_id' => 31, 'debitcreditflag' => 2, ])->orderBy(['account_transaction_id' => SORT_DESC])->one();
                                        
                                        $tax_amount = $transaction->amount;
                                    }
                                    
                                break;
                              //default:
                                //code to be executed if n is different from all labels;
                            }

                            return $model->amount - $tax_amount;
                        
                            /*** BACKUP CODE : Start ***/
                            /*if($model->osdv->request->creditor->tagged || $model->tax_registered)
                                $taxable_amount = round($model->amount / 1.12, 2);
                            else
                                $taxable_amount = $model->amount;

                            if($model->osdv->request->creditor->tagged || $model->amount > 10000.00){
                                $tax1 = round($taxable_amount * $model->rate1, 2);
                                $tax2 = round($taxable_amount * $model->rate2, 2);
                                $tax_amount = $tax1 + $tax2;
                            }else{
                                $tax_amount = round($taxable_amount * $model->rate1, 2);
                            }*/
                            /*** BACKUP CODE : End ***/
                        
                            /*if($model->taxable){
                                $tax_amount = 0.00;
                                if($model->tax_registered || $model->osdv->request->creditor->tagged)
                                    $taxable_amount = round($model->amount / 1.12, 2);
                                else
                                    $taxable_amount = $model->amount;
                                
                                if($model->osdv->request->creditor->tagged || $model->amount > 10000.00){
                                    $tax1 = round($taxable_amount * $model->rate1, 2);
                                    $tax2 = round($taxable_amount * $model->rate2, 2);
                                    $tax_amount = $tax1 + $tax2;
                                }else{
                                    $tax_amount = round($taxable_amount * $model->rate1, 2);
                                }
                                                                
                                if($model->debitcreditflag == 2)
                                    return round($model->amount - $tax_amount, 2);
                                else
                                    return round($tax_amount, 2);
                            }else{
                                return $model->amount;
                            }*/
                        
                        },
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->account_transaction_id],
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
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [   
                    'attribute'=>'amount',
                    'header' => 'Tax',
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'format' => 'raw',
                    'width'=>'80px',
                    'value'=>function ($model, $key, $index, $widget) { 
                        $btnCss = 'btn btn-success';
                        
                        if($model->taxable){
                            return Html::button('<i class="glyphicon glyphicon-list-alt"></i>', ['value' => Url::to(['accounttransaction/applytax', 'id'=>$model->account_transaction_id]), 'title' => Yii::t('app', "Apply Tax"), 'class' => $btnCss, 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonUploadAttachments']);
                        }else{
                            return '';
                        }
                        
                    },
                ],
                /*
                [
                    'attribute'=>'amount',
                    'header' => 'Tax Amount',
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: left; padding-left: 25px; vertical-align: middle; font-weight: bold;'],
                    'width'=>'750px',
                    'value'=>function ($model, $key, $index, $widget) { 
                        $fmt = Yii::$app->formatter;
                        $tax_amount = 0.00;
                        
                            //return $fmt->asDecimal($model->amount);
                        if($model->tax_registered)
                            $taxable_amount = $model->amount / 1.12;
                        else
                            $taxable_amount = $model->amount;
                        
                        if($model->amount < 10000.00){
                            $tax_amount = $taxable_amount * $model->rate1;
                        }else{
                            $tax_amount = ($taxable_amount * $model->rate1) + ($taxable_amount * $model->rate2);
                        }
                        
                        return $fmt->asDecimal($tax_amount);
                    },
                ],
                */
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
                    'before'=>Html::button('Add', ['value' => Url::to(['accounttransaction/additems', 'id'=>$model->osdv_id, 'year'=>$year]),     
                                                                'title' => 'Account Transactions', 'class' => 'btn btn-success'.($model->status_id == Request::STATUS_CERTIFIED_FUNDS_AVAILABLE ? 'disabled' : ''), 'style'=>'margin-right: 6px;'.(Yii::$app->user->can('access-finance-disbursement') ? '' : 'display: none;'), 'id'=>'buttonAddAccounttransaction']),
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
                    
                    $request_id = $model->request->request_id;
                    $record_id = $model->request_attachment_id;
                    $component_id = 20;
                    $comments = Comment::find()
                        ->where(['component_id' => $component_id, 'record_id' => $record_id])
                        ->count();
                    
                    return $model->attachment->name. ' ' . 
                        
                    //Html::button('', ['value' => Url::to(['request/comments', 'id'=>$model->request_id]), 'title' => 'comments', 'class' => 'glyphicon glyphicon-comment', 'id'=>'buttonComment']) .
                        
                    Html::a('<i class="fa fa-lg fa-comment"></i> '.$comments.' comments',[''], ['class' => 'btn btn-black', 'title' => 'Comments', 'onClick'=>               "{
                            //alert($(this).attr('title'));
                            //loadModal('comments?record_id=$record_id&component=$component_id'); 
                            loadModal('/system/comment/create?request_id=$request_id&record_id=$record_id&component=$component_id'); 
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
                'header' => 'Signed Attachments',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'format' => 'raw',
                'width'=>'80px',
                'value'=>function ($model, $key, $index, $widget) { 
                    $btnCss = 'btn btn-success';
                    return Requestattachment::hasSignedattachment($model->request_attachment_id) ? 
                    Html::button('<i class="glyphicon glyphicon-file"></i> View', ['value' => Url::to(['request/signedattachment', 'id'=>$model->signedattachment->request_attachment_signed_id]), 'title' => Yii::t('app', "Signed Attachment"), 'class' => $btnCss, 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonUploadAttachments']) 
                    : '';
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