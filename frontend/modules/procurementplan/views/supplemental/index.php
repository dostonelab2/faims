<?php

use yii\helpers\Html;
use yii\helpers\Url;

use kartik\editable\Editable;
use kartik\grid\GridView;
use kartik\detail\DetailView;
use yii\bootstrap\Modal;
use yii\web\View;
use common\models\procurement\Project;


//use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $searchModel common\models\procurementplan\SupplementalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Supplemental Items';
$this->params['breadcrumbs'][] = ['label' => $model->unit_id ? $model->unit->name : Project::findOne($model->project_id)->code, 'url' => ['ppmp/view', 'id' => $model->ppmp_id]];
$this->params['breadcrumbs'][] = $this->title;


Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modal',
    'size' => 'modal-lg',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

?>

<div class="ppmpitem-index">

      <?php
        $attributes = [
            [
                'group'=>true,
                'label'=>'PPMP DETAILS',                    
                'rowOptions'=>['class'=>'info'],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>$model->unit_id ? 'unit_id' : 'project_id',
                        'value'=>$model->unit_id ? $model->unit->name : Project::findOne($model->project_id)->code,
                        'valueColOptions'=>['style'=>'width:100px'],
                    ],
                    [
                        'attribute'=>'year',
                        'valueColOptions'=>['style'=>'width:30%'],
                        'label'=>'Year',
                    ],
                ],
            ],
            [
                'columns' => [
                    
                    [
                        'attribute'=>'division_id', 
                        'label'=>'Division',
                        'displayOnly'=>true,
                        'value'=>$model->project_id ? '' : $model->division->name,
                        'valueColOptions'=>['style'=>'width:100px'],
                    ],  
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>$model->project_id ? 'unit_id' : 'project_id',
                        'valueColOptions'=>['style'=>'width:100px'],
                    ],
                ],
            ],
            [
                'group'=>true,
                'label'=>'BUDGET ALLOCATION',
                'rowOptions'=>['class'=>'info'],
                //'groupOptions'=>['class'=>'text-center']
            ],
            [
                'attribute'=>'charged_to',
                'label'=>'Approved Budget (Php)',
                'value'=>$model->project_id ? $model->project->budgetallocation->getTotal() : $model->unit->budgetallocation->getTotal(),
                'format'=>['decimal', 2],
                'inputContainer' => ['class'=>'col-sm-6'],
            ],
            [
                'attribute'=>'year',
                'label'=>'Running Total (Php)',
                'value'=>$model->getRunningTotal(),
                'format'=>['decimal', 2],
                'inputContainer' => ['class'=>'col-sm-6'],
            ],
            [
                'label'=>'Remaining Budget (Php)',
                'value'=>($model->project_id ? $model->project->budgetallocation->getTotal() : $model->unit->budgetallocation->getTotal()) - $model->getRunningTotal(),
                //'value'=>$model->unit->budgetallocation ? ($model->unit->budgetallocation->getTotal() - $model->getRunningTotal()) : - $model->getRunningTotal(),
                'format'=>['decimal', 2],
                'inputContainer' => ['class'=>'col-sm-6'],
                // hide this in edit mode by adding `kv-edit-hidden` CSS class
                'rowOptions'=>['class'=>'warning kv-edit-hidden', 'style'=>'border-top: 5px double #dedede; texl-align: right;'],
            ],
        ];
        
        echo DetailView::widget([
            'model' => $model,
            'mode'=>DetailView::MODE_VIEW,
            /*'deleteOptions'=>[ // your ajax delete parameters
                'params' => ['id' => 1000, 'kvdelete'=>true],
            ],*/
            'container' => ['id'=>'kv-demo'],
            //'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
            
            //'buttons1' => '', //hides buttons on detail view
            'attributes' => $attributes,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            'panel' => [
                //'type' => 'Primary', 
                'heading'=>'<i class="glyphicon glyphicon-book"></i> PPMP - '.($model->project_id ? Project::findOne($model->project_id)->code : $model->unit->name).' - '.$model->year,
                'type'=>DetailView::TYPE_PRIMARY,
                //'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
            ],

            'buttons1' => false,
        ]);
    ?>

</div>

<?php

$gridColumns = [

                [
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '20px',
                    'header' => '',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    //'mergeHeader' => true,
                ],
                [
                    'attribute'=>'month',
                    'header'=>'Month',
                    'visible' =>  $dataProvider->totalCount > 0 ? true : false,
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            if($model->month == 1){
                                return 'January';
                            }elseif ($model->month == 2) {
                                return 'February';
                            }elseif ($model->month == 3) {
                                return 'March';
                            }elseif ($model->month == 4) {
                                return 'April';
                            }elseif ($model->month == 5) {
                                return 'May';
                            }elseif ($model->month == 6) {
                                return 'June';
                            }elseif ($model->month == 7) {
                                return 'July';
                            }elseif ($model->month == 8) {
                                return 'August';
                            }elseif ($model->month == 9) {
                                return 'September';
                            }elseif ($model->month == 10) {
                                return 'October';
                            }elseif ($model->month == 11) {
                                return 'November';
                            }elseif ($model->month == 12) {
                                return 'December';
                            }
                        },
                    'headerOptions' => ['style' => 'text-align: left; background-color: #7e9fda;'],
                    'contentOptions' => ['style' => 'text-align: left; background-color: #7e9fda; font-weight: bold;'],
                
                    'group'=>true,  // enable grouping,
                    'groupedRow'=>true,                    // move grouped column to a single grouped row
                    'groupOddCssClass'=>'',  // configure odd group cell css class
                    'groupEvenCssClass'=>'', // configure even group cell css class
                    
                ],
                [
                    'attribute'=>'description', 
                    'header'=>'General Description',
                    'width'=>'300px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: left'],
                    'mergeHeader' => true,
                ],
                         [
                    'attribute'=>'unit', 
                    'header'=>'Unit of Measure',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->unittype->name_short ? $model->unittype->name_short : '';
                        },
                    'width'=>'100px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: center'], 
                    'mergeHeader' => true,
                ],
                [
                    'attribute'=>'cost',
                    'header'=>'Unit Cost',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal($model->cost);
                        },
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
       
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=> 'quantity',
                    'header'=>'Quantity',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'refreshGrid'=>true,
                    //'readonly' => !$isApproval AND !$isMember,
                    
                    'readonly' => function($model){
                        if($model->status_id == 2){
                            return true;
                        }
                    },
                    'editableOptions'=> function ($model , $key , $index){
                        return [
                            'options' => ['id' => $index . '_' . $model->ppmp_item_id . '-q'],
                            'placement'=>'left',
                            'disabled'=> $model->status_id != 0 AND !Yii::$app->user->can('approved-ppmp'),
                            'name'=>'quantity',
                            'asPopover' => true,
                            'value' => $model->quantity,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/procurementplan/supplemental/updateqty', 'month' => $model->month]], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'attribute'=>'estimated_budget',
                    'header'=>'Estimated Budget',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal($model->getTotalamount());
                        },
                    //'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                  ['class' => 'kartik\grid\ActionColumn',
                    'template' => '{button}',    
                    'buttons' => [
                        'button' => function ($url,$model) use ($isMember)
                        {
                    
                            if($model->status_id == 0){
                                if($isMember){
                                 return Html::a('Submit <span class="glyphicon glyphicon-hand-right"></span>', ['submit', 'item_id' => $model->ppmp_item_id, 'ppmp_id' => $model->ppmp->ppmp_id], [
                                                                    'class' => 'btn btn-primary',
                                                                    'style' => 'margin-right: 6px; display: ""; width: 80px;',
                                                                    'data' => [
                                                                        'confirm' => 'Are you sure you want to submit this item?',
                                                                        'method' => 'post',],]);
                                }
                    
                            }elseif($model->status_id == 1){
                                if(Yii::$app->user->can('approved-ppmp')){

                                    return Html::a('Approve <span class="glyphicon glyphicon-thumbs-up"></span>', ['approve', 'item_id' => $model->ppmp_item_id, 'ppmp_id' => $model->ppmp->ppmp_id], [
                                                                    'class' => 'btn btn-success',
                                                                    'style' => 'margin-right: 6px; display: ""; width: 80px;',
                                                                    'data' => [
                                                                        'confirm' => 'Are you sure you want to approve this item?',
                                                                        'method' => 'post',],]);
                                }else{
                                    return 'Submitted';
                                }
                            }elseif($model->status_id == 2){
                                    return 'Approved';
                            }
                             
                        },
              
                    ], 
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => function($model){
                                            if($model->status_id == 1){
                                                if(Yii::$app->user->can('approved-ppmp')){
                                                    return ['style' => 'background-color: white;'];
                                                }else{
                                                    return ['style' => 'background-color: #a8d5ff; color: black;'];
                                                }
                                            }elseif($model->status_id == 2){
                                                return ['style' => 'background-color: #6aad6e; color: black;'];
                                            }
                                    },
                ],
            
            ];


echo GridView::widget([
        'dataProvider'=> $dataProvider,
        //'filterModel' => $searchModel,
        /*
        'rowOptions' => function($model){
                if($model->status_id == 1){
                    return ['style' => 'background-color: #eba4a8'];
                }elseif($model->status_id == 2){
                    return ['style' => 'background-color: #90eefc'];
                }
        },*/
        'columns' => $gridColumns,
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'pjax'=>true,
        /*
        'pjaxSettings' => [
                    'options' => [
                            'enablePushState' => false,
                            'id' => 'supplementalppmp',
                        ],
                    ],
                    */
        //'responsive'=>false,
        //'hover'=>true,
        'panel' => [
                'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Supplemental Items</h3>',
                'type'=>'primary',
                //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Create Country', ['create'], ['class' => 'btn btn-success']),
                //'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
                'footer'=>false
            ], 

        'toolbar' => 
                                [
                                    [
                                        'content' => Html::button('Approve All ('. $countapprovalDataProvider->totalCount .')  <i class="glyphicon glyphicon-thumbs-up"></i>', [
                                                        'value' => Url::to(['supplemental/approveall', 'id'=>$model->ppmp_id]),
                                                        'disabled' => !Yii::$app->user->can('approved-ppmp') ? true : $countapprovalDataProvider->totalCount == 0 ? true : false,
                                                        'title' => 'Supplemental Items',
                                                        'class' => 'btn btn-success', 
                                                        'style' =>'margin-right: 6px; display: ""; height: 35px;', 
                                                        'id' => 'btnApproveAll']).
                                                    Html::button('Submit All ('. $countsubmitDataProvider->totalCount .') <i class="glyphicon glyphicon-hand-right"></i>', [
                                                        'value' => Url::to(['supplemental/submitall', 'id'=>$model->ppmp_id]),
                                                        'disabled' => !$isMember ? true : $countsubmitDataProvider->totalCount == 0 ? true : false,
                                                        'title' => 'Supplemental Items',
                                                        'class' => 'btn btn-info', 
                                                        'style' =>'margin-right: 6px; display: ""; height: 35px;', 
                                                        'id' => 'btnSubmitAll']).
                                                    Html::button('Add Item  <i class="glyphicon glyphicon-list"></i>', [
                                                        //'value' => Url::to(['supplemental/additems', 'id'=>$model->ppmp_id, 'selectMonth' => 0]),
                                                        'value' => Url::to(['supplemental/addsupplementalitems', 'id'=>$model->ppmp_id, 'year'=>$model->year]),
                                                        'disabled' => $model->status_id != 3 ? true : !$isMember ? true : false,
                                                        'title' => 'Supplemental Items',
                                                        'class' => 'btn btn-primary', 
                                                        'style' =>'margin-right: 6px; display: ""; height: 35px;', 
                                                        'id' => 'buttonSupplementalItem'])
                                       
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



$js=<<<JS
$(document).ready(function() {
    $("body").on("click","#btnApproveAll",function () {
            var url = $(this).val();
            var status = 2;
            krajeeDialog.confirm("Are you sure you want to Approve all this item?", function (result) {
            if (result) {
                $.ajax({
                        type: "POST",
                        url: url,
                        data: {status:status},
                        //contentType: "application/json; charset=utf-8",
                        dataType: "json",
                });
            } else {
                krajeeDialog.alert('Oops! You declined!');
            }
        });
    });
    $("body").on("click","#btnSubmitAll",function () {
            var url = $(this).val();
            var status = 1;
            krajeeDialog.confirm("Are you sure you want to submit all this item for approval?", function (result) {
            if (result) {
                $.ajax({
                        type: "POST",
                        url: url,
                        data: {status:status},
                        //contentType: "application/json; charset=utf-8",
                        dataType: "json",
                });
            } else {
                krajeeDialog.alert('Oops! You declined!');
            }
        });
    });
});
JS;


$this->registerJs($js,View::POS_READY);

?>

