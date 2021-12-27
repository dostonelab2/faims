<?php

/**
 * Created by Larry Mark B. Somocor.
 * User: Larry
 * Date: 3/13/2018
 * Time: 9:47 AM
 */


use yii\helpers\Html;
use yii\helpers\Url;
use common\modules\pdfprint;
use common\components\Functions;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\bootstrap\Modal;


$func = new Functions();


$BaseURL = $GLOBALS['frontend_base_uri'];
$this->title = 'Purchase Order';
$angularcontroller = "";
$this->params['breadcrumbs'][] = '';
//$this->registerJsFile($BaseURL.'js/angular.min.js');
//$this->registerJsFile($BaseURL.'js/ui-bootstrap-tpls-0.10.0.min.js');
//$this->registerJsFile($BaseURL.'js/app.js');
$this->registerJsFile($BaseURL.'js/jquery.tabletojson.js');
$this->registerJsFile($BaseURL.'js/procurement/purchaseorder/purchaseorder.js');
$this->registerJsFile($BaseURL.'js/custom.js');
$this->registerJsFile($BaseURL.'js/sweetalert.min.js');
?>

<div class="request-index">
    <h1 class="centered" style="margin-bottom: 0px;"><i class="fa fa-sitemap"></i> <?= Html::encode($this->title) ?></h1>

    <?php
    //Modal
    Modal::begin([
        'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
        'id' => 'modalPurchaseOrder',
        'size' => 'modal-lg',
        'options'=> [
            'tabindex'=>false,
        ]
    ]);
    echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'>
   
        </div></div>";
    Modal::end();
    ?>



    <!-- content -->
    <?php

    function filter($item) {
 
        $supplierfilter =Yii::$app->request->getQueryParam('filtersupplier', '');
        $pofilter = Yii::$app->request->getQueryParam('filterpo', '');
        $bidsdescriptionfilter = Yii::$app->request->getQueryParam('filterbidsdescription', '');
        if (strlen($pofilter) > 0) {
            if (strpos($item['purchase_order_number'], $pofilter) != false) {
                return true;
            } else {
                return false;
            }
        }
        elseif (strlen($supplierfilter) > 0) {
            if (strpos($item['supplier_name'], $supplierfilter) != false) {
                return true;
            } else {
                return false;
            }
        }  
        elseif (strlen($bidsdescriptionfilter) > 0) {
            if (strpos($item['bids_item_description'], $bidsdescriptionfilter) != false) {
                return true;
            } else {
                return false;
            }
        }  else {
            return true;
        }
    }       
    $mdata = array_filter($mydata,'filter');
    $dataprovider = new ArrayDataProvider([
        'allModels' => $mdata,
        'pagination' => [
            'pageSize' => 12,
        ],
        'sort' => [
            'attributes' => ['purchase_order_number','supplier_name','bids_item_description'],
        ],
    ]);
    $pofilter = Yii::$app->request->getQueryParam('filterpo', '');
    $supplierfilter =Yii::$app->request->getQueryParam('filtersupplier', '');
    $bidsdescriptionfilter =Yii::$app->request->getQueryParam('filterbidsdescription', '');
    $searchModel = ['purchase_order_number' => $pofilter , 'supplier_name' => $supplierfilter, 'bids_item_description'=> $bidsdescriptionfilter];

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
            'label'=>'Purchase Order Number',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'filter'=> '<div class="col-lg-9"><input class="form-control" placeholder="Search..." name="filterpo" id="filterpo" value="'. $searchModel['purchase_order_number'] .'" type="text"></div>
                        <div class="col-lg-3"><button class="btn btn-primary btn-block"><i class="fa fa-search-plus" style="font-size: 14px;"></i></button></div>',
            'group'=>true,  // enable grouping
            'subGroupOf'=>1, // supplier column index is the parent group
            'width'=>'25%',
        ],

        [
            'attribute'=>'supplier_name',
            'label'=>'Supplier Name',
            'width'=>'15%',
            'filter'=> '<div class="col-lg-9"><input class="form-control" placeholder="Search..." name="filtersupplier" id="filtersupplier" value="'. $searchModel['supplier_name'] .'" type="text"></div>
            <div class="col-lg-3"><button class="btn btn-primary btn-block"><i class="fa fa-search-plus" style="font-size: 14px;"></i></button></div>',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
        ],

        [
            'attribute'=>'bids_unit',
            'label'=>'Unit',
            'width'=>'5%',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
        ],

        [
            'attribute'=>'bids_item_description',
            'label'=>'Item Description',
            'vAlign' => 'top',
            'format'=>'raw',
            'filter'=> '<div class="col-lg-9"><input class="form-control" placeholder="Search..." name="filterbidsdescription" id="filterbidsdescription" value="'. $searchModel['bids_item_description'] .'" type="text"></div>
            <div class="col-lg-3"><button class="btn btn-primary btn-block"><i class="fa fa-search-plus" style="font-size: 14px;"></i></button></div>',
            'contentOptions' => [
                'style'=>'max-width:200px; overflow: auto; white-space: normal; word-wrap: break-word;'
            ],
            'width'=>'45%',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
        ],

        [
            'attribute'=>'bids_quantity',
            'label'=>'Quantity',
            'width'=>'5%',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
        ],



        [
            'attribute'=>'bids_price',
            'label'=>'Price',
            'width'=>'10%',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
        ],
        [

            'label'=>'Status',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'group'=>true,  // enable grouping
            'subGroupOf'=>1, // supplier column index is the parent group
            'format'=>'raw',
            'value' => function ($data) use ($func) {
                if ($data['purchase_order_status'] == 2){
                    return '<span class="badge" style="background:#FF0000;">Canceled <i class="fa fa-remove"></i></span>';
                } 
                if ($data['purchase_order_status'] == 1){
                    return '<span class="badge" style="background:#005cf0;">Active <i class="fa fa-check"></i></span>';
                }    
            },
        ],
        [

            'label'=>'Modify',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'format'=>'raw',
            'value' => function ($data) use ($func) {
                $btn ="<h5 style='text-align:center;display: inline-block;margin:0px;' data-step='2' data-intro='Click here to view Obligation Request'><span>". Html::button('<span class=\'glyphicon glyphicon-pencil\'></span>', ['value' => Url::to(['viewpo?id='.$data["bids_details_id"].'&&'.'mid='.$data["purchase_order_id"]]), 'title' => 'Modify Purchase Order', 'tab-index'=>0 , 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddObligation'])."</span></h5>";
                return $btn;     
            },
        ],
        [

            'label'=>'Actions',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'group'=>true,  // enable grouping
            'subGroupOf'=>1, // supplier column index is the parent group
            'format'=>'raw',
            'value' => function ($data) use ($func) {
                if ($data['purchase_order_status'] == 1){
                    return Html::button(
                        '<span class="glyphicon glyphicon-remove-sign"></span>',
                        [
                            'title' => 'Cancel PO',
                            'value' => $data['purchase_order_number'],
                            'class' => 'btn btn-danger btncancelpo',
                            'id' => 'btncancelpo'
                        ]
                    ) .
                     Html::a('<span class="glyphicon glyphicon-print"></span>', ['reportpofull?id='.$data["purchase_order_number"].'&&'.'mid='.$data["purchase_order_id"]], [
                        'class'=>'btn-pdfprint btn btn-primary',
                        'data-pjax'=>"0",
                        'pjax'=>"0",
                        'title'=>'Will open the generated PDF file in a new window'
                    ]);
                }else{
                    return '';
                }
            },
        ],

    ];

    echo GridView::widget([
        'id' => 'kv-grid-data',
        'dataProvider'=> $dataprovider,
        'filterModel' => $searchModel,
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
            'heading' => '',
        ],
        'persistResize' => false,
        'toggleDataOptions' => ['minCount' => 10],
        'exportConfig' => true,
    ]); 
    ?>

    <?= pdfprint\Pdfprint::widget([
        'elementClass' => '.btn-pdfprint'
    ]); ?>

    <!-- *********************************** Generate Header Modal for Create ************************************************
                        GenerateHeaderModal (id,title,widthsize,topheight)
    -->
    <?php //$func->GenerateHeaderModal("purchaseorder","Purchase Order",'80',2) ?>
   <!-- <div class="request-bids">
        <div class= "loadpartial">
            <img src="/images/loading.gif">
        </div>
        <div id="purchaseorderview">Units	SIGN PEN, RED, liquid/gel ink, 0.5mm needle tip, refillable	8	42.00
            8	BAMBOO GARDEN SOCIAL HALL & CATERING SERVICES	Units	CLIP, BACKFOLD, all metal, clamping: 19mm	15	75.00
            9	BAMBOO GARDEN SOCIAL HALL & CATERING SERVICES	Units	GLUE, all purpose, gross weight: 200 grams min	9	88.00
            10	PO-18-09-0004	GARDEN ORCHID HOTEL
        </div>
    </div>
    -->
    <?php
    //$func->GenerateFooterModal("Close","Proceed",0);
    ?>
    <!-- *********************************** Close for View ************************************************
                            GenerateFooterModal(title,nextbuttiontitle,allowbutton=booloean)
    -->

    <?php //$BaseURL; ?>
</div>


