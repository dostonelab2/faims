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
use common\models\finance\Request;
use common\models\finance\Requestattachment;
use common\models\finance\Requesttype;
use common\models\system\Comment;
/* @var $this yii\web\View */
/* @var $model common\models\finance\Request */

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
?>
<!--pre>
<?php //print_r($request_status);?>
</pre-->


<?php //echo $this->render('_stepper'); ?>
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
            'displayOnly'=>true
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
        ],

        [
            'attribute'=>'amount',
            'label'=>'Amount (P)',
            'format'=>['decimal', 2],
            'inputContainer' => ['class'=>'col-sm-6'],
        ],
        /*[
            'attribute'=>'status_id',
            'label'=>'Status',
            'inputContainer' => ['class'=>'col-sm-6'],
        ],*/
        [
            'group'=>true,
            'label'=>'Status',
            'rowOptions'=>['class'=>'table-info']
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
        
        'buttons1' => ( (Yii::$app->user->identity->username == 'Admin') || $model->owner() ) ? '{update}' : '', //hides buttons on detail view
        'attributes' => $attributes,
        'condensed' => true,
        'responsive' => true,
        'hover' => true,
        'formOptions' => ['action' => ['request/view', 'id' => $model->request_id]],
        'panel' => [
            //'type' => 'Primary', 
            'heading'=>'FINANCIAL REQUEST',
            'type'=>DetailView::TYPE_PRIMARY,
            //'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
        ],
    ]); ?>
    
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
                    //$component_id = Comment::COMPONENT_ATTACHMENT;
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
                'before'=>Html::button('View Attachments', ['value' => Url::to(['request/viewattachments', 'id'=>$model->request_id]),     
                                                            'title' => 'Attachments', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;'.($model->attachments ? 'display: none;' : ''), 'id'=>'buttonViewAttachments']) . 
                
                                                            Html::button('Submit for Verification', ['value' => Url::to(['request/submitforverification', 'id'=>$model->request_id]), 'title' => 'Submit', 'class' => $params['btnClass'], 'style'=>'margin-right: 6px;'.((($model->status_id < Request::STATUS_SUBMITTED)) ? ($model->attachments ? '' : 'display: none;') : 'display: none;'), 'id'=>'buttonSubmitForVerification']) .
                
                                                            //Yii::$app->user->can('access-finance-verification')
                                                            Html::button('Submit for Validation', ['value' => Url::to(['request/submitforvalidation', 'id'=>$model->request_id]), 'title' => 'Submit', 'class' => $params['btnClass'], 'style'=>'margin-right: 6px;'.(((($model->status_id >= Request::STATUS_SUBMITTED) && ($model->status_id < Request::STATUS_VERIFIED) && Yii::$app->user->can('access-finance-verification') )) ? ($model->attachments ? '' : 'display: none;') : 'display: none;'), 'id'=>'buttonSubmitForValidation']) .
                                                            
                                                            //Yii::$app->user->can('access-finance-validation')
                                                            Html::button('Validate Request', ['value' => Url::to(['request/validate', 'id'=>$model->request_id]), 'title' => 'Submit', 'class' => $params['btnClass'], 'style'=>'margin-right: 6px;'.(((($model->status_id >= Request::STATUS_VERIFIED) && ($model->status_id < Request::STATUS_VALIDATED) && Yii::$app->user->can('access-finance-validation') )) ? ($model->attachments ? '' : 'display: none;') : 'display: none;'), 'id'=>'buttonValidateRequest']) .
                                                            (($model->status_id <= 20) ? "" : 
                                                            '<div class="alert '.$request_status["alert"].'" style="width: 20%; ">
                                                                Status: <strong>'.strtoupper($request_status["msg"]).'</strong>
                                                            </div>')
                                                            ,
                
                //Html::button('Submit', ['value' => Url::to(['request/submit', 'id'=>$model->request_id]), 'title' => 'Submit', 'class' => $params['btnClass'], 'style'=>'margin-right: 6px;'.((($model->status_id < Request::STATUS_SUBMITTED)) ? '' : 'display: none;'), 'id'=>'buttonSubmit']),
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