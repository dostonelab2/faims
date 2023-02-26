<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

use kartik\detail\DetailView;
use kartik\editable\Editable;
use kartik\grid\GridView;
use kartik\widgets\SwitchInput;


use yii\bootstrap\Modal;

use common\models\cashier\Creditor;
use common\models\finance\Request;
use common\models\finance\Requestattachment;
use common\models\finance\Requesttype;
use common\models\finance\Project;
use common\models\finance\Projecttype;
use common\models\finance\Obligationtype;
use common\models\procurement\Division;
use common\models\system\Comment;
/* @var $this yii\web\View */
/* @var $model common\models\finance\Request */


//$this->registerCssFile("@web/css/style.bundle.css");

$this->title = $model->request_number;
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

Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalContainerLg',
    'size' => 'modal-lg',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();
?>

<div class="row">
    <div class="col-sm-8">

        <?php $attributes = [
                /*[
                    'group'=>true,
                    //'label'=>'<center>LDDAP-ADA</center>',
                    'rowOptions'=>['class'=>'info'],
                ],*/
                [
                    'group'=>true,
                    'label'=>'Details',
                    'rowOptions'=>['class'=>'info']
                ],
                [
                    'attribute'=>'request_number',
                    'label'=>'Request Number',
                    'inputContainer' => ['class'=>'col-sm-6'],
                ],
                [
                    'attribute'=>'request_type_id',
                    'label'=>'Request Type',
                    'inputContainer' => ['class'=>'col-sm-6'],
                    'value' => $model->requesttype->name,
                    'type'=>DetailView::INPUT_SELECT2, 
                    'widgetOptions'=>[
                        'data'=>ArrayHelper::map(Requesttype::find()->orderBy(['name'=>SORT_ASC])->all(),'request_type_id','name'),
                        'options' => ['placeholder' => 'Select Type'],
                        'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                    ],
                ],
                [
                    'attribute'=>'obligation_type_id',
                    'label'=>'Fund Source',
                    'inputContainer' => ['class'=>'col-sm-6'],
                    'value' => $model->fundsource->name,
                    'type'=>DetailView::INPUT_SELECT2, 
                    'widgetOptions'=>[
                        'data'=>ArrayHelper::map(Obligationtype::find()->all(),'type_id','name'),
                        'options' => ['placeholder' => 'Fund Source', 'id'=>'fund_source_id'],
                        'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                    ],
                ],
                [
                    'attribute'=>'project_type_id',
                    'label'=>'Project Type',
                    'inputContainer' => ['class'=>'col-sm-6'],
                    'value' => $model->project_type_id ? $model->projecttype->name : '-',
                    'type'=>DetailView::INPUT_DEPDROP, 
                    'widgetOptions'=>[
                        'data'=>ArrayHelper::map(Projecttype::find()->all(),'project_type_id','name'),
                        'options' => ['placeholder' => 'Select Project Type', 'id'=>'project_type_id'],
                        'pluginOptions' => [
                            'depends'=>['fund_source_id'],
                            'allowClear'=>true, 
                            'width'=>'100%',
                            'url'=>Url::to(['request/listprojecttype'])
                        ],
                    ],
                ],
                [
                    'attribute'=>'project_id',
                    'label'=>'Project Name',
                    'inputContainer' => ['class'=>'col-sm-6'],
                    'value' => $model->project_id ? $model->project->name : '-',
                    'type'=>DetailView::INPUT_DEPDROP, 
                    'widgetOptions'=>[
                        'data'=>ArrayHelper::map(Project::find(0),'project_id','name'),
                        'options' => ['placeholder' => 'Select Project'],
                        'pluginOptions' => [
                            'depends'=>['project_type_id'],
                            'allowClear'=>true, 
                            'width'=>'100%',
                            'url'=>Url::to(['request/listproject'])
                        ],
                    ],
                ],
                [
                    'attribute'=>'division_id',
                    'label'=>'Division',
                    'inputContainer' => ['class'=>'col-sm-6'],
                    'value' => $model->division->name,
                    'type'=>DetailView::INPUT_SELECT2, 
                    'widgetOptions'=>[
                        'data'=>ArrayHelper::map(Division::find()->all(),'division_id','name'),
                        'options' => ['placeholder' => 'Select Type'],
                        'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                    ],
                ],
                [
                    'attribute'=>'payee_id',
                    'label'=>'Payee',
                    'inputContainer' => ['class'=>'col-sm-6'],
                    'value' => $model->creditor->name,
                    'type'=>DetailView::INPUT_SELECT2, 
                    'widgetOptions'=>[
                        'data'=>ArrayHelper::map(Creditor::find()->orderBy(['name'=>SORT_ASC])->all(),'creditor_id','name'),
                        'options' => ['placeholder' => 'Select Payee'],
                        'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                    ],
                ],
                [
                    'attribute'=>'particulars',
                    'label'=>'Particulars',
                    'inputContainer' => ['class'=>'col-sm-6'],
                    'type'=>DetailView::INPUT_TEXTAREA, 
                    'options'=>['rows'=>4]
                ],
                [
                    'attribute'=>'amount',
                    'label'=>'Amount (P)',
                    'format'=>['decimal', 2],
                    'inputContainer' => ['class'=>'col-sm-6'],
                ],
                [
                    'group'=>true,
                    'label'=>'Status',
                    'rowOptions'=>['class'=>'table-success']
                ],
                [
                        'attribute'=>'request_id',
                        'label'=>'',
                        'inputContainer' => ['class'=>'col-sm-2'],
                        'format' => 'raw',

                        'value' => Html::button('Track Progress', ['value' => Url::to(['request/tracking', 'id'=>$model->request_id]), 'title' => 'Track Request', 'class' => 'btn btn-md btn-success', 'id'=>'buttonTrackProgress'])
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
            
            'buttons1' => ( (Yii::$app->user->identity->username == 'Admin') || $model->owner() || Yii::$app->user->can('access-finance-verification')) ? '{update}' : '', //hides buttons on detail view
            'attributes' => $attributes,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            'formOptions' => ['action' => ['request/view', 'id' => $model->request_id]],
            'panel' => [
                //'type' => 'Primary', 
                'heading'=>'<i class="fas fa-newspaper-o"></i>  FINANCIAL REQUEST',
                'type'=>DetailView::TYPE_PRIMARY,
                //'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
            ],
        ]); ?>
    </div>

    <div class="col-sm-4">
    <?php $gridColumnsBudgetAllocationAssigment = [
                // [
                //     'attribute'=>'amount',
                //     'label'=>'Amount (P)',
                //     'format'=>['decimal', 2],
                //     // 'displayOnly'=>($model->status_id >= Request::STATUS_VALIDATED) ? true : false,
                //     'inputContainer' => ['class'=>'col-sm-6'],
                // ],
                [
                        'attribute'=>'request_id',
                        'label'=>'',
                        // 'inputContainer' => ['class'=>'col-sm-2'],
                        'format' => 'raw',

                        'value' => Html::button('Track Progress', ['value' => Url::to(['request/tracking', 'id'=>$model->request_id]), 'title' => 'Track Request', 'class' => 'btn btn-md btn-success', 'id'=>'buttonTrackProgress'])
                ],
            ];?>

        
        <?= /*GridView::widget([
            'id' => 'budget-allocation-assignment',
            'dataProvider' => $budgetallocationassignmentDataProvider,
            //'filterModel' => $searchModel,
            // 'showFooter' => true,
            // 'showPageSummary' => true,
            'columns' => $gridColumnsBudgetAllocationAssigment, // check the configuration for grid columns by clicking button above
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            // set left panel buttons
            'panel' => [
                'heading' => '<h3 class="panel-title">Attachments</h3>',
                'type' => GridView::TYPE_PRIMARY,
                'before'=> '',    
                //Html::button('Submit', ['value' => Url::to(['request/submit', 'id'=>$model->request_id]), 'title' => 'Submit', 'class' => $params['btnClass'], 'style'=>'margin-right: 6px;'.((($model->status_id < Request::STATUS_SUBMITTED)) ? '' : 'display: none;'), 'id'=>'buttonSubmit']),
                'after'=>false,
            ],
            'persistResize' => false,
            'toggleDataOptions' => ['minCount' => 10],
        ]);*/
        GridView::widget([
            'id' => 'budget-allocation',
            'dataProvider' => $budgetallocationassignmentDataProvider,
            // 'filterModel' => $searchModel,
            'columns' => $gridColumnsBudgetAllocationAssigment, // check this value by clicking GRID COLUMNS SETUP button at top of the page
            //'headerContainer' => ['style' => 'top:50px', 'class' => 'kv-table-header'], // offset from top
            'floatHeader' => true, // table header floats when you scroll
            //'floatPageSummary' => true, // table page summary floats when you scroll
            //'floatFooter' => false, // disable floating of table footer
            'pjax' => false, // pjax is set to always false for this demo
            // parameters from the demo form
            'responsive' => false,
            'bordered' => true,
            'striped' => false,
            'condensed' => true,
            'hover' => true,
            //'showPageSummary' => true,
            'panel' => [
                'after' => '',//<div class="float-right float-end"><button type="button" class="btn btn-primary" onclick="var keys = $("#kv-grid-demo").yiiGridView("getSelectedRows").length; alert(keys > 0 ? "Downloaded " + keys + " selected books to your account." : "No rows selected for download.");"><i class="fas fa-download"></i> Download Selected</button></div><div style="padding-top: 5px;"><em>* The page summary displays SUM for first 3 amount columns and AVG for the last.</em></div><div class="clearfix"></div>',
                // 'heading' => '<i class="fas fa-tasks"></i>  LIB Assignment '.Html::button('<i class="fas fa-plus" style="float: right;"></i>', ['value' => Url::to(['budgetallocationassignment/create', 'id'=>$model->request_id]), 'title' => 'Assign', 'class' => 'btn-sm btn-success', 'style'=>'float:right;', 'id'=>'buttonAssign']),
                'heading' => '<i class="fas fa-tasks"></i> LIB Assignment '.Html::a('<i class="fas fa-plus" style="float: right;"></i>', ['/budget/budgetallocationassignment/create', 'id' => $model->request_id], ['id'=>'buttonAssign', 'onclick'=>"$('#modalCreditor').modal('show')"]),
                'type' => 'primary',
                'before' => '',//Html::button('Assign', ['value' => Url::to(['budgetallocationassignment/create', 'id' => $model->request_id]), 'title' => 'Assign', 'class' => 'btn-sm btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonAssign']),
            ],
            // set export properties
            // set your toolbar
            'toolbar' =>  [
                [
                    'content' => '',
                        /*Html::button('<i class="fas fa-plus"></i>', [
                            'class' => 'btn btn-success',
                            'title' => 'Add Assignment',
                            'onclick' => 'alert("This should launch the book creation form.\n\nDisabled for this demo!");'
                        ]) , */
                    'options' => ['class' => 'btn-group mr-2 me-2']
                ],
                //'{export}',
                //'{toggleData}',
            ],
            'toggleDataContainer' => ['class' => 'btn-group mr-2 me-2'],
            'persistResize' => false,
            'toggleDataOptions' => ['minCount' => 10],
            'itemLabelSingle' => 'Assignment',
            'itemLabelPlural' => 'Assignments'
        ]);
        
    
        ?>    
    </div>
</div>
    <?php //if( ($model->payroll == 1 && Yii::$app->user->can('access-finance-disbursement')) || (Yii::$app->user->identity->username == 'Admin')) { ?>
    <?php //if($model->payroll == 1) { ?>
    <!--?php
    $gridColumnsPayroll = [
            [
                'class' => 'kartik\grid\SerialColumn',
                'contentOptions' => ['class' => 'kartik-sheet-style'],
                'width' => '10px',
                'header' => '',
                'headerOptions' => ['style' => 'text-align: center; width: 10px;'],
                'pageSummary' => '',  
                
                //'pageSummary' => '<span style="float:right";>SUBTOTAL<BR>DISCOUNT<BR><B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL</B></span>',  

            ],
            
            //'name',
            
            [   
                'attribute'=>'creditor_id',
                'pageSummary'=>'Total',
                'header' => 'Name',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: left; vertical-align: middle;'],
                'format' => 'raw',
                'width'=>'650px',
                'value'=> function ($model, $key, $index, $widget) { 
                    return $model->creditor->name;
                },
                'pageSummary' => 'TOTAL', 
                'pageSummaryOptions' => ['style' => 'text-align: right;'],
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
                        'options' => ['id' => $index . '_' . $model->request_payroll_id],
                        'placement'=>'left',
                        'disabled'=>!Yii::$app->user->can('access-finance-obligation'),
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
                'width'=>'100px',
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
                'pageSummaryOptions' => ['style' => 'text-align: right; padding-right: 25px;'],
            ],
            [   
                'attribute'=>'creditor_id',
                'header' => 'DV Number',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: left; vertical-align: middle;'],
                'format' => 'raw',
                'width'=>'150px',
                'value'=> function ($model, $key, $index, $widget) { 
                    return '';
                },
                'pageSummary' => false,
            ],
        ];
?-->
    
       <!--?= GridView::widget([
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
 
            'panel' => [
                'heading' => '<h3 class="panel-title">Payroll Items</h3>',
                'type' => GridView::TYPE_SUCCESS,
                //'before'=> Html::button('Add Creditors', ['value' => Url::to(['request/payrollitems', 'id'=>$model->request_id]), 'title' => 'Submit', 'class' =>'btn btn-success', 'style'=>'margin-right: 6px;'.((($model->status_id < Request::STATUS_SUBMITTED)) ? ($model->attachments ? '' : 'display: none;') : 'display: none;'), 'id'=>'buttonPayrollitems']) ,
                'before'=> Html::button('Add Creditors', ['value' => Url::to(['request/payrollitems', 'id'=>$model->request_id]), 'title' => 'Submit', 'class' =>'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonPayrollitems']) ,
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

    
    ?-->
        
    <?php //} ?>
    
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
                'width'=>'650px',
                'format' => 'raw',
                'value'=>function ($model, $key, $index, $widget) { 
                    
                    $request_id = $model->request->request_id;
                    $record_id = $model->request_attachment_id;
                    //$component_id = Comment::COMPONENT_ATTACHMENT;
                    $component_id = 20;
                    
                    $comments = Comment::find()
                        ->where(['component_id' => $component_id, 'record_id' => $record_id])
                        ->count();

                    return $model->attachment->name. ' ' . 
                        
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
                'visible' => ( $model->owner() || Yii::$app->user->can('access-finance-verification') || Yii::$app->user->can('access-finance-validation') || Yii::$app->user->can('access-finance-processing') || Yii::$app->user->can('access-inspection') || Yii::$app->user->can('access-finance-documentcollation') || Yii::$app->user->can('access-cashiering')),
                //'visible' => (Yii::$app->user->can('access-finance-verification') ),
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
                'visible' => ( $model->owner() || Yii::$app->user->can('access-finance-verification') || Yii::$app->user->can('access-finance-validation') || Yii::$app->user->can('access-finance-processing')),
                'value'=>function ($model, $key, $index, $widget){ 

                    $link = "/uploads/finance/request/" . $model->request->request_number. "/" . $model->filename.'.pdf';
                    /*$btnCss = [];
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
                    
                    return Html::button('<i class="glyphicon glyphicon-file"></i> View', ['value' => Url::to(['request/uploadattachmenttest', 'id'=>$model->request_attachment_id]), 'title' => Yii::t('app', "Attachment"), 'class' => $btnCss, 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonUploadAttachmentstest']);*/
                    
                    //return Yii::$app->controller->renderPartial('_attachments');
                    return Html::a('<i class="glyphicon glyphicon-file"></i> View', $link, ['target' => '_blank', 'class' => 'btn btn-primary']);
                        
                },
            ],
            [   
                'attribute'=>'filename',
                'header' => 'Signed Attachments',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'format' => 'raw',
                'width'=>'80px',
                'visible' => ( $model->owner() || Yii::$app->user->can('access-finance-verification') || Yii::$app->user->can('access-finance-validation') || Yii::$app->user->can('access-finance-processing')),
                /*'value'=> function ($model, $key, $index, $widget) { 
                    return Requestattachment::hasSignedattachment($model->request_attachment_id) ? $model->signedattachment->request_attachment_signed_id : '';
                },*/
                'value'=>function ($model, $key, $index, $widget) { 
                    $btnCss = 'btn btn-success';
                    
                    //return Requestattachment::hasSignedattachment($model->request_attachment_id);
                    return Requestattachment::hasSignedattachment($model->request_attachment_id) ? 
                    Html::button('<i class="glyphicon glyphicon-file"></i> View', ['value' => Url::to(['request/signedattachment', 'id'=>$model->signedattachment->request_attachment_signed_id]), 'title' => Yii::t('app', "Signed Attachment"), 'class' => $btnCss, 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonUploadAttachments']) 
                    : '';
                },
            ],
            [   
                'attribute'=>'filecode',
                'header' => 'File Code',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'format' => 'raw',
                'width'=>'80px',
                /*'value'=>function ($model, $key, $index, $widget) { 
                    return Requestattachment::generateCode($model->request_attachment_id);
                },*/
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute'=>'require_signed',
                'header' => 'Require Signed',
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'width'=>'60px',
                'visible' => !Yii::$app->user->can('access-finance-verification'),
                //'value'=>function ($model, $key, $index, $widget) { 
                    //return $model->status_id;
                //},
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute'=>'status_id',
                'header' => 'Verified',
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'width'=>'60px',
                'visible' => !Yii::$app->user->can('access-finance-verification'),
                //'value'=>function ($model, $key, $index, $widget) { 
                    //return $model->status_id;
                //},
            ],
            
            /*[
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'require_signed',
                'header' => 'Required Signed?',
                'format' => 'raw',
                'refreshGrid'=>true,
                'visible' => Yii::$app->user->can('access-finance-verification'),
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'value'=>function ($model, $key, $index, $widget) { 
                    return $model->require_signed ? '<i class="glyphicon glyphicon-ok"></i>' : '<i class="glyphicon glyphicon-remove text-red"></i>';
                },
                'editableOptions'=> function ($model , $key , $index) {
                                    return [
                                        'options' => ['id' => $index . '_10_' . $model->require_signed],
                                        'contentOptions' => ['style' => 'text-align: center;  vertical-align:middle;'],
                                        'placement'=>'left',
                                        //'disabled'=>!$model->status_id,
                                        'name'=>'district',
                                        'asPopover' => true,
                                        'value'=>function ($model, $key, $index, $widget) {
                                            return $model->require_signed ? '<i class="glyphicon glyphicon-ok"></i>' : '<i class="glyphicon glyphicon-remove text-red"></i>';
                                        },
                                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                                        'data'=>['0'=>'No', '1'=>'Yes'],
                                        'formOptions'=>['action' => ['/finance/request/togglestatus']], // point to the new action
                                    ];
                                },
                'hAlign' => 'right', 
                'vAlign' => 'middle',
                'width' => '7%',
                //'format' => ['decimal', 2],
                'pageSummary' => true
            ],*/
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'status_id',
                'header' => 'Verified',
                'format' => 'raw',
                'refreshGrid'=>true,
                'visible' => Yii::$app->user->can('access-finance-verification'),
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'value'=>function ($model, $key, $index, $widget) { 
                    return $model->status_id ? '<i class="glyphicon glyphicon-ok"></i>' : '<i class="glyphicon glyphicon-remove text-red"></i>';
                },
                'editableOptions'=> function ($model , $key , $index) {
                                    return [
                                        'options' => ['id' => $index . '_11_' . $model->status_id],
                                        'contentOptions' => ['style' => 'text-align: center;  vertical-align:middle;'],
                                        'placement'=>'left',
                                        'disabled'=>!$model->status_id,
                                        'name'=>'district',
                                        'asPopover' => true,
                                        'value'=>function ($model, $key, $index, $widget) {
                                            return $model->status_id ? '<i class="glyphicon glyphicon-ok"></i>' : '<i class="glyphicon glyphicon-remove text-red"></i>';
                                        },
                                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                                        'data'=>['0'=>'Mark Unverified'],
                                        'formOptions'=>['action' => ['/finance/request/togglestatus']], // point to the new action
                                    ];
                                },
                'hAlign' => 'right', 
                'vAlign' => 'middle',
                'width' => '7%',
                //'format' => ['decimal', 2],
                'pageSummary' => true
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
                'heading' => '<i class="fas fa-files-o"></i>  Attachments',
                'type' => GridView::TYPE_PRIMARY,
                //'before'=> (($model->status_id == Request::STATUS_VALIDATED) || ($model->status_id == Request::STATUS_VERIFIED)) ? 
                'before'=> (Yii::$app->user->can('access-finance-validation') || Yii::$app->user->can('access-finance-verification')) ? 
                
                (                                           '<h5 data-step="1" data-intro="Indicate the details of this financial request.">'.Html::button('View Attachments', ['value' => Url::to(['request/viewattachments', 'id'=>$model->request_id]),                                             'title' => 'Attachments', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;'.
                                                            ($model->attachments ? 'display: none;' : ''), 'id'=>'buttonViewAttachments']).'</h5>' . 
                
                                                            Html::button('Submit for Verification', ['value' => Url::to(['request/submitforverification', 'id'=>$model->request_id]), 'title' => 'Submit', 'class' => $params['btnClass'], 'style'=>'margin-right: 6px;'.((($model->status_id < Request::STATUS_SUBMITTED)) ? ($model->attachments ? '' : 'display: none;') : 'display: none;'), 'id'=>'buttonSubmitForVerification']) .
                
                                                            //Yii::$app->user->can('access-finance-verification')
                                                            Html::button('Submit for Validation', ['value' => Url::to(['request/submitforvalidation', 'id'=>$model->request_id]), 'title' => 'Submit', 'class' => $params['btnClass'], 'style'=>'margin-right: 6px;'.(((($model->status_id >= Request::STATUS_SUBMITTED) && ($model->status_id < Request::STATUS_VERIFIED) && Yii::$app->user->can('access-finance-verification') )) ? ($model->attachments ? '' : 'display: none;') : 'display: none;'), 'id'=>'buttonSubmitForValidation']) .
                                                            
                                                            //Yii::$app->user->can('access-finance-validation')
                                                            Html::button('Validate Request', ['value' => Url::to(['request/validate', 'id'=>$model->request_id]), 'title' => 'Submit', 'class' => $params['btnClass'], 'style'=>'margin-right: 6px;'.(((($model->status_id >= Request::STATUS_VERIFIED) && ($model->status_id < Request::STATUS_VALIDATED) && Yii::$app->user->can('access-finance-validation') )) ? ($model->attachments ? '' : 'display: none;') : 'display: none;'), 'id'=>'buttonValidateRequest']) )
                
                                                            :
                
                                                            (
                                                            $model->status_id == Request::STATUS_CREATED ?
                                                                
                                                            Html::button('View Attachments', ['value' => Url::to(['request/viewattachments', 'id'=>$model->request_id]),                                             'title' => 'Attachments', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;'.
                                                            ($model->attachments ? 'display: none;' : ''), 'id'=>'buttonViewAttachments']) .
                                                                
                                                            Html::button('Submit for Verification', ['value' => Url::to(['request/submitforverification', 'id'=>$model->request_id]), 'title' => 'Submit', 'class' => $params['btnClass'], 'style'=>'margin-right: 6px;'.((($model->status_id < Request::STATUS_SUBMITTED)) ? ($model->attachments ? '' : 'display: none;') : 'display: none;'), 'id'=>'buttonSubmitForVerification'])    
                                                            :
                                                            
                                                            '<div class="alert '.$request_status["alert"].'" style="width: 20%; ">
                                                                Status: <strong>'.strtoupper($request_status["msg"]).'</strong>
                                                            </div>'
                                                            ),
                
                //Html::button('Submit', ['value' => Url::to(['request/submit', 'id'=>$model->request_id]), 'title' => 'Submit', 'class' => $params['btnClass'], 'style'=>'margin-right: 6px;'.((($model->status_id < Request::STATUS_SUBMITTED)) ? '' : 'display: none;'), 'id'=>'buttonSubmit']),
                'after'=>false,
            ],
            // set right toolbar buttons
            'toolbar' => 
                            [
                                [
                                    'content'=>
                                        Html::button('View Recent Requests', ['value' => Url::to(['request/recent', 'id'=>$model->request_id, 'payee_id'=>$model->payee_id]), 'title' => 'Requests', 'class' => 'btn btn-warning', 'style'=>'margin-right: 6px;', 'id'=>'buttonViewRecent']) .
                                        Html::button('View Documents', ['value' => Url::to(['request/viewdocuments', 'id'=>$model->request_id]), 'title' => 'Documents', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonViewDocuments']) . 
                                    
                                        ( ($model->status_id > 40) ?
                                        ( (($model->obligation_type_id == 1) ? Html::a('Obligation Request  <i class="glyphicon glyphicon-print"></i>', Url::to(['request/printos', 'id'=>$model->request_id]), ['target' => '_blank', 'data-pjax'=>0, 'class'=>'btn btn-primary']) : "") .'<a></a>'.
                                        Html::a('Disbursement Voucher  <i class="glyphicon glyphicon-print"></i>', Url::to(['request/printdv', 'id'=>$model->request_id]), ['target' => '_blank', 'data-pjax'=>0, 'class'=>'btn btn-primary']) ) : '')
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

<br><br>
<?php 
//return Yii::$app->controller->renderPartial('_request_payroll', ['dataProvider' => $dataProvider, 'id'=>$id]);
//echo Yii::$app->controller->renderPartial('_attachments');

?>

<a id="startButton"  href="javascript:void(0);">Show guide</a>

<script type="text/javascript">

$("#modalContainer").on("hidden.bs.modal", function () {
    $.pjax.reload({container:'#payroll-items'});
});
    
function startIntro(){
    var intro = introJs();
      intro.setOptions({
        steps: [
          {
            intro: "Hello world!"
          },
          {
            element: document.querySelector('#step1'),
            intro: "This is a tooltip."
          },
          {
            element: document.querySelectorAll('#step2')[0],
            intro: "Ok, wasn't that fun?",
            position: 'right'
          },
          {
            element: '#step3',
            intro: 'More features, more fun.',
            position: 'left'
          },
          {
            element: '#step4',
            intro: "Another step.",
            position: 'bottom'
          },
          {
            element: '#step5',
            intro: 'Get it, use it.'
          }
        ]
      });

      intro.start();
      }
</script>