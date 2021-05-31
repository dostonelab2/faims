<?php

use yii\helpers\Html;

use kartik\helpers\Panel;
use kartik\select2\Select2;
use kartik\widgets\SwitchInput  ;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\procurement\BidsdetailSearch;
use common\components\Functions;
use common\models\procurement\Supplier;
use common\modules\pdfprint;
use common\components\MyPrint;
$func = new Functions();
$con =  Yii::$app->db;

$BaseURL = $GLOBALS['frontend_base_uri'];

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Department */
/* @var $form yii\widgets\ActiveForm */



//$this->registerJsFile($BaseURL.'js/angular.min.js');
//$this->registerJsFile($BaseURL.'js/ui-bootstrap-tpls-0.10.0.min.js');
//$this->registerJsFile($BaseURL.'js/app.js');
$this->registerJsFile($BaseURL.'js/jquery.tabletojson.js');
$this->registerJsFile($BaseURL.'js/procurement/bids/bids.js');


//$this->registerJsFile($BaseURL.'js/custom.js');
?>

<div class="bids-form">
    <?php //$form = \kartik\form\ActiveForm::begin(['options' => ['data-pjax' => true ]]);?>
    <h5><a id="startButton2"  href="javascript:void(0);"><img src="<?= $BaseURL;?>images\help.png" height="52" width="98" style="padding: 10px;"></a></h5>
    <div class="form-group">
        <div class="row">
            <div class="col-lg-12">
                <input type="hidden" id="pID" name="pID"  value="<?= $model->purchase_request_id; ?>">
                <h4 style="border-bottom: #1c1c1c 2.5px solid;text-transform: uppercase;">PR Request No: <?= $model->purchase_request_number ?></h4>
            </div>
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" id="myhome" href="<?= $BaseURL.'procurement/bids/'?>#home">Bids <i class="fa fa-barcode"></i> </a></li>
                <li><a data-toggle="tab" id="myabstract" href="<?= $BaseURL.'procurement/bids/'?>#abstract">Bids Summary <i class="fa fa-archive"></i></a></li>
                <li><a data-toggle="tab" id="myawarded" href="<?= $BaseURL.'procurement/bids/'?>#awarded">Awards <i class="fa fa-won"></i></a></li>
            </ul>
            <div class="col-lg-12">
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">

                            <div class="col-lg-12">
                                <div id="homeids-container">
                                    <div class="popup-container" style="width: 40%;margin: 0 auto!important;">
                                        <div class="mypopup">
                                            <div class="col-lg-12">
                                                <h1 align="center" style="color: red;">No Changes Detected <br/> Please Select A Supplier</h1>
                                            </div>
                                            <div style="position: absolute;bottom: 0;right: 0;"><button id="changespopup" class="popupclose btn btn-warning btn-lg"> <i class="fa fa-remove"></i></button> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div id="disable-container">
                                    <div class="popup-container" style="margin: 0 auto!important;width: 100%!important;">
                                        <div class="mypopup" style="height:450px!important;padding:0px!important;opacity: 0.7!important;left:0;right:0;">
                                            <div class="col-lg-12">
                                                <div style="height: 200px;"></div>
                                                <h1 align="center" style="color: #FFFFFF;opacity:0.7;">Bids Already Exist for this Supplier</h1>
                                            </div>
                                            <div style="position: absolute;bottom: 0;right: 0;"><button id="disablepopup" class="popupclose btn btn-warning btn-lg"> <i class="fa fa-remove"></i></button> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h3>Bids</h3>
                            <!-- Bids Container -->
                            <div class="row">
                                <div class="col-lg-1">
                                    <div class="space-20"></div>
                                    <h5 style='margin:0px;' data-step='1' data-intro='Click here to Add New'><span><button id="btnAddBids" class="btn btn-success btn-md"><i class="fa fa-plus"></i> Add New</button></span></h5>
                                </div>
                                <div class="col-lg-4">
                                    <div class="space-20"></div>
                                    <h5 style='margin:0px;' data-step='2' data-intro='Select A Supplier'><span>
                                    <?php
                                    echo $func->GetSupplier('',$supp,true,"Supplier",$model->purchase_request_id);
                                    ?>
                                        </span></h5>
                                </div>
                                <div class="col-lg-1">
                                    <div class="space-20"></div>
                                    <h5 style='margin:0px;' data-step='3' data-intro='Add Bids (Before Saving bids must have a value in the GRID'><span>
                                    <button id="btnCreatePO" name="btnCreatePO" class="btn btn-lg btn-primary"><i class="fa fa-envelope-o"></i> Save Bids</button></span></h5>
                                </div>
                                <div class="col-lg-6">
                                    <label>Remarks Status</label>
                                    <div><span class="badge" style="background: red;">Pending <i class="fa fa-remove"></i></span>
                                        <span class="badge" style="background: blue;">For Bids <i class="fa fa-check-circle"></i></span>
                                        <span class="badge" style="background: green;">Awarded <i class="fa fa-check-circle"></i></span>
                                       <!-- <span class="badge" style="background: green;">Awarded <i class="fa fa-check"></i></span> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-scroll" style="">
                                        <div id="a_page_loader" data-id="">
                                            <div class="control-options false">
                                                <div class="control-options-items">
                                                    <h1 id="tbl-item-selected"> selected</h1>
                                                </div>
                                            </div>

                                            <span style="float: right;display: inline-block!important;"><a href="#" id="max-scroll"><i class="fa fa-caret-down"></i> <span id="scroll-description"> Maximize </span> </a></span>
                                            <div style="clear: both;"></div>
                             <!-- content -->
                                            <?php

                                            $colorPluginOptions =  [
                                                'showPalette' => true,
                                                'showPaletteOnly' => true,
                                                'showSelectionPalette' => true,
                                                'showAlpha' => false,
                                                'allowEmpty' => false,
                                                'preferredFormat' => 'name',
                                                'palette' => [
                                                    [
                                                        "white", "black", "grey", "silver", "gold", "brown",
                                                    ],
                                                    [
                                                        "red", "orange", "yellow", "indigo", "maroon", "pink"
                                                    ],
                                                    [
                                                        "blue", "green", "violet", "cyan", "magenta", "purple",
                                                    ],
                                                ]
                                            ];

                                            $gridColumns = [

                                                [
                                                    'class' => 'kartik\grid\SerialColumn',
                                                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                                                    'width' => '5%',
                                                    'vAlign' => 'top',
                                                    'header' => '',
                                                    'headerOptions' => ['class' => 'kartik-sheet-style']
                                                ],
                                                [
                                                    'class' => 'kartik\grid\DataColumn',
                                                    'format'=>'html',
                                                    'header'=>'Remarks',
                                                    'value'=>function($model,$key,$index,$widget) {
                                                        switch ($model->purchase_request_details_status) {
                                                            case 0:
                                                                return '<span class="badge btn-block" style="background: red;">Pending <i class="fa fa-remove"></i></span>';
                                                                break;
                                                            case 1:
                                                                return '<span class="badge btn-block" style="background: blue;">For Bids <i class="fa fa-check-circle"></i></span>';
                                                                break;
                                                            case 2:
                                                                return '<span class="badge btn-block" style="background: green;">Awarded <i class="fa fa-check-circle"></i></span>';
                                                                break;
                                                        }
                                                    },
                                                    'headerOptions' => ['class' => '','style'=>'color:#337ab7;'],
                                                ],

                                                [
                                                    'attribute'=>'unit_id',
                                                    'label'=>'Unit',
                                                    'width'=>'10%',
                                                    'value'=>function ($model, $key, $index, $widget) {
                                                        return 'Units';
                                                    },
                                                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                                                ],

                                                [
                                                    'attribute'=>'purchase_request_details_item_description',
                                                    'label'=>'Item Description',
                                                    'width'=>'60%',
                                                    'format'=>'raw',
                                                    'contentOptions' => [
                                                        'style'=>'max-width:200px; overflow: auto; white-space: normal; word-wrap: break-word;'
                                                    ],
                                                    'value'=>function ($model, $key, $index, $widget) {
                                                        return $model->purchase_request_details_item_description;
                                                    },
                                                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                                                ],

                                                ['attribute'=>'purchase_request_details_quantity',
                                                 'label'=> 'QTY',
                                                 'vAlign' => 'right',
                                                 'hAlign' => 'right',
                                                 'width' => '5%',
                                                 'pageSummary'=> true,
                                                 'headerOptions' => ['class' => '','style'=>'margin-left:100px!important;text-align:right!important;'],
                                                 'contentOptions'=> ['style'=>''],
                                                ],

                                                [
                                                    'class' => 'kartik\grid\EditableColumn',
                                                    'label'=> 'Price',
                                                    'attribute' => 'purchase_request_details_price',
                                                    'refreshGrid'=>true,
                                                    'readonly' => function($model, $key, $index, $widget) {
                                                        return ($model->purchase_request_details_status==2); // do not allow editing of inactive record
                                                    },
                                                   'editableOptions' =>function ($model, $key, $index) {
                                                        return [
                                                            'header'=>'Price',
                                                            'format' => ['decimal', 2],
                                                            'convertFormat'=>false,
                                                            'size'=>'sm',
                                                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                                                            'options' => [
                                                                'pluginOptions' => ['min' => 0, 'max' => 999999999,['decimal', 2]],
                                                                'pluginEvents' => [
                                                                    "editableSuccess"=>"function(event, val, form, data) { alert('Successful submission of value ' + val); }",
                                                                ]
                                                            ],
                                                            'formOptions'=>['action' => ['bids/editPrice']], // point to the new action
                                                        ];
                                                    },
                                                    ],


                                                [
                                                    'class' => 'kartik\grid\FormulaColumn',
                                                    'header' => 'Total Cost',
                                                    'vAlign' => 'top',
                                                    'format' => ['decimal', 2],
                                                    'attribute'=> 'purchase_request_details_total',
                                                    'value' => function ($model, $key, $index, $widget) {
                                                        $p = compact('model', 'key', 'index');
                                                       return $widget->col(4, $p) * $widget->col(5, $p);
                                                    },
                                                    'headerOptions' => ['class' => '','style'=>'color:#337ab7!important;'],
                                                    'hAlign' => 'right',
                                                    'width' => '10%',
                                                    'pageSummary' => true,

                                                ],

                                                [
                                                    'class' => 'kartik\grid\CheckboxColumn',
                                                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                                                    'vAlign' => 'top',
                                                    'checkboxOptions' => function($model, $key, $index, $column) {
                                                        if($model->purchase_request_details_status==2) {
                                                            $bool = false;
                                                            return ['checked' => $bool,
                                                                    'disabled'=>true];
                                                        }else{
                                                            return ['checked' => $model->purchase_request_details_status,
                                                                    'disabled'=>true];
                                                        }
                                                    },
                                                ],



                                            ];

                                            echo GridView::widget([
                                                'id' => 'kv-grid-data',
                                                'dataProvider'=> $dataProvider,
                                                //'filterModel' => $searchModel,
                                                'pjax' => true,
                                                'columns' => $gridColumns,
                                                'pjaxSettings' => [
                                                    'neverTimeout'=>true,
                                                    'options' => [
                                                        'enablePushState' => false,
                                                    ],
                                                ],

                                                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                                                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                                                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                                                'toolbar' =>  [
                                                    ['content'=>''
                                                    ],
                                                    '{export}',
                                                    '{toggleData}',
                                                ],
                                                // set export properties
                                                'export' => [
                                                    'fontAwesome' => true
                                                ],

                                                'bordered' => true,
                                                'striped' => true,
                                                'condensed' => true,
                                                'responsive' => true,
                                                'hover' => true,
                                                'showPageSummary' => true,
                                                'panel' => [
                                                    'type' => GridView::TYPE_PRIMARY,
                                                    'heading' => 'Data Details',
                                                ],
                                                'persistResize' => false,
                                                'toggleDataOptions' => ['minCount' => 3000000],
                                                'exportConfig' => true,
                                            ]);
                                            ?>
                                        </div>
                                    </div>

                                    <div id="testquery">

                                    </div>
                                </div>
                            </div>
                            <!-- Close Bids Container -->
                            <div class="row">
                                <div class="col-lg-3">

                                   <?php //$options['type'] = 'button'; //make it a normal button
                                         //Html::button($label, $options);?>
                                </div>
                            </div>
                        </div>


                    <div id="abstract" class="tab-pane fade">
                        <div class="col-lg-12">
                            <div id="bids-container">
                                <div class="popup-container" style="width: 40%;margin: 0 auto!important;">
                                    <div class="mypopup">
                                        <div class="col-lg-12">
                                            <h1 align="center" style="color: #fff9e5;">Bids Generated Successfully</h1>
                                            <h1 align="center" style="color: red;">under PR#: <?= $model->purchase_request_number ?></h1>
                                        </div>
                                        <div style="position: absolute;bottom: 0;right: 0;"><button id="bidspoupclose" class="popuclose btn btn-warning btn-lg"> <i class="fa fa-remove"></i></button> </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div id="disabled-container">
                                <div class="popup-container" style="width: 40%;margin: 0 auto!important;">
                                    <div class="mypopup">
                                        <div class="col-lg-12">
                                            <h1 align="center" style="color: red;">No available data to create a Purchase Order</h1>
                                        </div>
                                        <div style="position: absolute;bottom: 0;right: 0;"><button id="changesspopup" class="popupclose btn btn-warning btn-lg"> <i class="fa fa-remove"></i></button> </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="space-20"></div>
                            <div class="col-lg-2">

                                <?php
                                echo '<label class="control-label">Lot Bids</label>';
                                echo SwitchInput::widget([
                                    'name' => 'chkBids',
                                    'type' => SwitchInput::CHECKBOX,
                                    'value'=> true,
                                    'pluginOptions' => [
                                        'size' => 'sm',
                                        'onColor' => 'success',
                                        'offColor' => 'danger',
                                        'onText'=>'YES',
                                        'offText'=>'NO'
                                    ]
                                ]);
                                ?>
                            </div>
                            <div class="col-lg-5">
                                <label>Remarks Status</label>
                                <div>
                                    <span class="badge" style="background: green;">Available for Award <i class="fa fa-toggle-on"></i></span>
                                    <span class="badge" style="background: black;">Pending for Award <i class="fa fa-pencil"></i></span>
                                    <span class="badge" style="background: red;">Not Available for Award <i class="fa fa-recycle"></i></span>
                                    <span class="badge" style="background: blue;">Awarded <i class="fa fa-check-circle"></i></span>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="space-20"></div>
                                <button id="btnCreatePurchaseOrder" name="btnCreatePurchaseOrder" class="btn btn-lg btn-block btn-success">Create Purchase Order <i class="fa fa-plus"></i></button>
                            </div>
                            <div class="col-lg-2">
                                <div class="space-20"></div>
                                <a href="reportabstract?id=<?=$model->purchase_request_id?>" class="btn-pdfprint btn btn-lg btn-block btn-danger">Print <i class="fa fa-print"></i></a>
                                <?= pdfprint\Pdfprint::widget([
                                    'elementClass' => '.btn-pdfprint'
                                ]);?>
                            </div>
                            <div class="col-lg-12">

                          <div class="table-scroll" style="">

                                    <span style="float: right;display: inline-block!important;"><a href="#" id="max-scroll"><i class="fa fa-caret-down"></i> <span id="scroll-description"> Maximize </span> </a></span>
                                    <div style="clear: both;"></div>

                                <?php

                                $searchModelBidDetails = new BidsdetailSearch();
                                //$searchModelBidDetails->with='purchaserequest';
                                $bidsDetailProvider = $searchModelBidDetails->search(Yii::$app->request->queryParams);
                               /* $bidsDetailProvider->sort->defaultOrder = [
                                   'purchase_request_details_id'=>'asc',
                                ];*/
                                $bidsDetailProvider->sort->sortParam = false;
                                $bidsDetailProvider->pagination  = false;
                                $bidsDetailProvider->query->where('`purchase_request_id`='. $model->purchase_request_id);
                                $gridColumns = [

                                    [
                                        'class' => 'kartik\grid\SerialColumn',
                                        'contentOptions' => ['class' => 'kartik-sheet-style'],
                                        'width' => '5%',
                                        'vAlign' => 'top',
                                        'header' => '',
                                        'headerOptions' => ['class' => 'kartik-sheet-style']
                                    ],

                                    [

                                        'attribute'=>'supplier_id',
                                        'value'=>function ($data) {
                                             return $data->bid->supplier->supplier_name;
                                        },
                                        'group'=>true,  // enable grouping,
                                        'groupedRow'=>true,                    // move grouped column to a single grouped row
                                        'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                                        'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                                    ],


                                    [
                                        'attribute'=>'bids_item_description',
                                        'label'=>'Item Description',
                                        'format'=>'raw',
                                        'contentOptions' => [
                                            'style'=>'max-width:200px; overflow: auto; white-space: normal; word-wrap: break-word;'
                                        ],
                                        'enableSorting'=> 'false',
                                        'width'=>'60%',
                                        'value'=>function($data){
                                            return $data->bids_item_description;
                                        },
                                        'headerOptions' => ['class' => 'kartik-sheet-style'],
                                    ],

                                    [
                                        'attribute'=>'supplier_id',
                                        'label'=>'Supplier',
                                        'enableSorting'=> 'false',
                                        'width'=>'60%',
                                        'value'=>function($data){
                                            return $data->bid->supplier->supplier_name;
                                        },
                                        'headerOptions' => ['class' => 'kartik-sheet-style'],
                                    ],


                                    [
                                        'class' => 'kartik\grid\DataColumn',
                                        'format'=>'html',
                                        'header'=>'Status',
                                        'contentOptions' => function($model){
                                            return ['id' => 'status-badge-'.$model->bids_details_id];
                                        },
                                        'value'=>function($model,$key,$index,$widget) {
                                            switch ($model->bids_details_status) {
                                                case 0:
                                                    return '<span class="badge btn-block" style="background: green;">Available for Award <i class="fa fa-toggle-on"></i></span>';
                                                    break;
                                                case 1:
                                                    return '<span class="badge btn-block" style="background: blue;">Awarded <i class="fa fa-check-circle"></i></span>';
                                                    break;
                                                case 3:
                                                    return '<span class="badge btn-block" style="background: black;">Pending for Award <i class="fa fa-pencil"></i></span>';
                                                    break;
                                                case 4:
                                                    return '<span class="badge btn-block" style="background: red;">Not Available for Award <i class="fa fa-recycle"></i></span>';
                                                    break;
                                            }
                                        },
                                        'headerOptions' => ['class' => '','style'=>'color:#337ab7;'],
                                    ],

                                    [
                                        'class' => 'kartik\grid\EditableColumn',
                                        'label'=> 'Remarks',
                                        'attribute' => 'bids_remarks',
                                        'refreshGrid'=>true,
                                        'editableOptions' =>function ($data) {
                                            return [
                                                'header'=>'Remarks',
                                                'size'=>'sm',
                                                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                                                'options' => [
                                                    'pluginEvents' => [
                                                        "editableSuccess"=>"function(event, val, form, data) { alert('Successful submission of value ' + val); }",
                                                    ]
                                                ],
                                                'formOptions'=>['action' => ['bids/editRemarks']], // point to the new action
                                            ];
                                        },
                                    ],


                                    [
                                        'attribute'=>'bids_quantity',
                                        'label'=>'Qty',
                                        'enableSorting'=> 'false',
                                        'width'=>'60%',
                                        'value'=>function($data){
                                            return $data->bids_quantity;
                                        },
                                        'headerOptions' => ['class' => 'kartik-sheet-style'],
                                    ],



                             /*       [
                                        'attribute'=>'bids_price',
                                        'label'=>'Price',
                                        'enableSorting'=> 'false',
                                        'width'=>'60%',
                                        'value'=>function($data){
                                            return $data->bids_price;
                                        },
                                        'headerOptions' => ['class' => 'kartik-sheet-style'],
                                    ],*/



                                    [
                                        'class' => 'kartik\grid\EditableColumn',
                                        'label'=> 'Price',
                                        'attribute' => 'bids_price',
                                        'pageSummary'=> true,
                                        'format' => ['decimal', 2],
                                        'refreshGrid'=>true,
                                        'readonly' => function($model, $key, $index, $widget) {
                                            //return ($model->purchase_request_details_status==1); // do not allow editing of inactive records
                                        },
                                        'editableOptions' =>function ($model, $key, $index) {
                                            return [
                                                'header'=>'Price',
                                                'format' => ['decimal', 2],
                                                'convertFormat'=>true,
                                                'size'=>'sm',
                                                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                                                'formOptions'=>['action' => ['bids/editPrice2']], // point to the new action
                                                'options'=>['pluginOptions'=>['min'=>0, 'max'=>50000000000,'step'=>100,'decimal'=>2 ,'prefix'=>'PHP']]
                                            ];
                                        },

                                    ],


                                    [
                                        'class' => '\kartik\grid\CheckboxColumn',

                                              'header'=> false,
                                              'vAlign' => 'top',
                                              'rowSelectedClass'=>GridView::TYPE_INFO,
                                              'checkboxOptions' => function($data) {
                                                       if($data->bids_details_status==2) {
                                                            $bool = false;
                                                            return ['checked' => $bool,
                                                                    'disabled'=>true];
                                                            }else{
                                                            if($data->bids_details_status==0) {
                                                             return ['checked' => $data->bids_details_status,
                                                                     'disabled'=>false];
                                                            }
                                                            if($data->bids_details_status==4) {
                                                                return ['checked' => false,
                                                                        'disabled'=>true];
                                                            }else{
                                                            return ['checked' => $data->bids_details_status,
                                                                    'disabled'=> $data->bids_details_status == 3 ? false : true];
                                                            }
                                                        }
                                                    },
                                    ],




                                    [
                                        'class' => '\kartik\grid\ActionColumn',
                                        'width'=>'6%',
                                        'template' => '{cancel} {delete}',                   // move grouped column to a single grouped row
                                        'buttons'=>[
                                            'cancel' => function($url,$model,$key){
                                                return Html::a('<span class="glyphicon glyphicon-remove-circle"></span>', ['cancelbids?id='.$model["bids_details_id"].
                                                '&pid='.$model["purchase_request_id"].'&bid='.$model["bids_id"].'&bid_status='.$model["bids_details_status"]], [
                                                        'class'=>'btn btn-warning',
                                                        'data-pjax'=>"1",
                                                        'pjax'=>"1",
                                                        'title'=>'Cancel Bids'
                                                    ]);
                                            },
                                            'delete' => function($url,$model,$key){
                                                return Html::a('<span class="glyphicon glyphicon-remove-circle"></span>', ['removebids?id='.$model["bids_details_id"].
                                                '&pid='.$model["purchase_request_id"].'&bid='.$model["bids_id"].'&bid_status='.$model["bids_details_status"]], [
                                                        'class'=>'btn btn-danger',
                                                        'data-pjax'=>"1",   
                                                        'pjax'=>"1",
                                                        'title'=>'Remove Bids'
                                                    ]);
                                            },
                                        ],
                                        'headerOptions' => ['class' => 'kartik-sheet-style'],
                                        'deleteOptions' => ['label' => '<span class="glyphicon glyphicon-remove"></span>'],
                                        'contentOptions' => function ($model, $key, $index, $column) {
                                            return ['style' => 'position:absolute;border:none;'];
                                        }
                                    ],

                                ];

                                echo GridView::widget([
                                    'id' => 'kv-grid-data2',
                                    'dataProvider'=> $bidsDetailProvider,
                                    //'filterModel' => $searchModel,
                                    'pjax' => true,
                                    'columns' => $gridColumns,
                                    'pjaxSettings' => [
                                        'neverTimeout'=>true,
                                        'options' => [
                                            'id'=>'mycontainersss',
                                            'enablePushState' => true,
                                        ],
                                    ],

                                    'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                                    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                                    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                                    'toolbar' =>  [
                                        ['content'=>''
                                        ],
                                        '{export}',
                                        '{toggleData}',
                                    ],
                                    // set export properties
                                    'export' => [
                                        'fontAwesome' => true
                                    ],

                                    'bordered' => true,
                                    'striped' => true,
                                    'condensed' => true,
                                    'responsive' => true,
                                    'hover' => true,

                                    'showPageSummary' => true,
                                    'panel' => [
                                        'type' => GridView::TYPE_SUCCESS,
                                        'heading' => 'Bids Summary',

                                    ],
                                    'persistResize' => false,
                                    'toggleDataOptions' => ['minCount' => 5000],
                                    'exportConfig' => true,
                                ]);

                                ?>

                                </div>




                            </div>

                        </div>


                    </div>


                        <div id="awarded" class="tab-pane fade">
                            <div class="col-lg-12">
                                <div id="awards-container">
                                    <div class="popup-container" style="width: 40%;margin: 0 auto!important;">
                                        <div class="mypopup">
                                            <div class="col-lg-12">
                                                <h1 align="center" style="color: #fff9e5;">PO Generated Successfully</h1>
                                                <h1 align="center" style="color: red;">PO-18-04-001</h1>
                                            </div>
                                            <div style="position: absolute;bottom: 0;right: 0;"><button id="popupcloseawards" class="btn btn-warning btn-lg"> <i class="fa fa-remove"></i></button> </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <h3>Awards</h3>
                            <!-- -->
                            <div class="table-scroll" style="">
                                <div id="a_page_loader" data-id="">
                                    <div class="control-options false">
                                        <div class="control-options-items">
                                            <h1 id="tbl-item-selected"> selected</h1>
                                        </div>
                                    </div>

                                    <span style="float: right;display: inline-block!important;"><a href="#" id="max-scroll"><i class="fa fa-caret-down"></i> <span id="scroll-description"> Maximize </span> </a></span>
                                    <div style="clear: both;"></div>
                                    <!-- content -->
                                    <?php

                                    $colorPluginOptions =  [
                                        'showPalette' => true,
                                        'showPaletteOnly' => true,
                                        'showSelectionPalette' => true,
                                        'showAlpha' => false,
                                        'allowEmpty' => false,
                                        'preferredFormat' => 'name',
                                        'palette' => [
                                            [
                                                "white", "black", "grey", "silver", "gold", "brown",
                                            ],
                                            [
                                                "red", "orange", "yellow", "indigo", "maroon", "pink"
                                            ],
                                            [
                                                "blue", "green", "violet", "cyan", "magenta", "purple",
                                            ],
                                        ]
                                    ];

                                    $gridColumns = [

                                        [
                                            'class' => 'kartik\grid\SerialColumn',
                                            'contentOptions' => ['class' => 'kartik-sheet-style'],
                                            'width' => '5%',
                                            'vAlign' => 'top',
                                            'header' => '',
                                            'headerOptions' => ['class' => 'kartik-sheet-style'],
                                        ],


                                        [
                                            'attribute'=>'purchase_order_number',
                                            'label'=>'Purchase Order Nymber',
                                            'headerOptions' => ['class' => 'kartik-sheet-style'],
                                            'group'=>true,  // enable grouping,
                                            'groupedRow'=>true,                    // move grouped column to a single grouped row
                                            'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                                            'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class

                                        ],

                                       [
                                            'attribute'=>'SupplierName',
                                            'label'=>'Supplier',
                                            'width'=>'10%',
                                            'headerOptions' => ['class' => 'kartik-sheet-style'],
                                        ],

                                        [
                                            'attribute'=>'bids_unit',
                                            'label'=>'Unit',
                                            'width'=>'10%',
                                            'headerOptions' => ['class' => 'kartik-sheet-style'],
                                        ],

                                        [
                                            'attribute'=>'bids_item_description',
                                            'label'=>'Item Description',
                                            'vAlign' => 'top',
                                            'format'=>'raw',
                                            'contentOptions' => [
                                                'style'=>'max-width:200px; overflow: auto; white-space: normal; word-wrap: break-word;'
                                            ],
                                            'width'=>'60%',
                                            'headerOptions' => ['class' => 'kartik-sheet-style'],
                                        ],


                                    /*    [
                                            'class' => 'kartik\grid\EditableColumn',
                                            'label'=> 'Remarks',
                                            'attribute' => 'bids_item_description',
                                            'value' => function($data) {
                                                return $data["bids_item_description"];
                                            },
                                            'refreshGrid'=>true,
                                            'editableOptions' =>function ($data) {
                                                return [
                                                    'header'=>'Remarks',
                                                    'size'=>'sm',
                                                    'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                                                    'options' => [
                                                        'pluginEvents' => [
                                                            "editableSuccess"=>"function(event, val, form, data) { alert('Successful submission of value ' + val); }",
                                                        ]
                                                    ],
                                                    'formOptions'=>['action' => ['bids/editRemarks']], // point to the new action
                                                ];
                                            },
                                        ],
                                    */

                                        [
                                            'attribute'=>'bids_quantity',
                                            'label'=>'Quantity',
                                            'width'=>'10%',
                                            'headerOptions' => ['class' => 'kartik-sheet-style'],
                                        ],

                                        [
                                            'attribute'=>'bids_price',
                                            'label'=>'Price',
                                            'width'=>'10%',
                                            'headerOptions' => ['class' => 'kartik-sheet-style'],
                                        ],

                                        [

                                            'label'=>'Actions',
                                            'headerOptions' => ['class' => 'kartik-sheet-style'],
                                            'group'=>true,  // enable grouping
                                            'subGroupOf'=>1, // supplier column index is the parent group
                                            'format'=>'raw',
                                            'value' => function ($data) use ($func) {
                                                return Html::a('<span class="glyphicon glyphicon-print"></span>', ['../procurement/purchaseorder/reportpo?id='.$data["purchase_order_number"]], [
                                                    'class'=>'btn-pdfprint btn btn-warning',
                                                    'data-pjax'=>"0",
                                                    'pjax'=>"0",
                                                    'title'=>'Will open the generated PDF file in a new window'
                                                ]);
                                            },
                                        ],


                                    ];

                                    echo GridView::widget([
                                        'id' => 'kv-grid-data3',
                                        'dataProvider'=> $ListPOprovider,
                                        //'filterModel' => $searchModel,
                                        'pjax' => true,
                                        'columns' => $gridColumns,
                                        'pjaxSettings' => [
                                            'neverTimeout'=>true,
                                            'options' => [
                                                'enablePushState' => false,
                                            ],
                                        ],

                                        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                                        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                                        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                                        'toolbar' =>  [
                                            ['content'=>''
                                            ],
                                            '{export}',
                                            '{toggleData}',
                                        ],
                                        // set export properties
                                        'export' => [
                                            'fontAwesome' => true
                                        ],

                                        'bordered' => true,
                                        'striped' => true,
                                        'condensed' => true,
                                        'responsive' => true,
                                        'hover' => true,
                                        'showPageSummary' => true,
                                        'panel' => [
                                            'type' => GridView::TYPE_PRIMARY,
                                            'heading' => 'Awards Details',
                                        ],
                                        'persistResize' => false,
                                        'toggleDataOptions' => ['minCount' => 3000000],
                                        'exportConfig' => true,
                                    ]);
                                    ?>
                                </div>
                            </div>

                            <div id="testquery">

                            </div>
                        </div>

            </div>
        </div>

    </div>

</div>

    <?= pdfprint\Pdfprint::widget([
        'elementClass' => '.btn-pdfprint'
    ]); ?>

    <script type="text/javascript">
        document.getElementById('startButton2').onclick = function() {
            introJs().setOption('doneLabel', 'Next page').start().oncomplete(function() {
                //window.location.href = 'index?multipage=true';
                var tabs = $('.nav-tabs > li');
                var active = tabs.filter('.active');
                var next = active.next('li').length ? active.next('li').find('a') : tabs.filter(':first-child').find('a');
                next.tab('show');
            });
        };
    </script>


