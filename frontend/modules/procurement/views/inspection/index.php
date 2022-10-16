<?php

/**
 * Created by Larry Mark B. Somocor.
 * User: Larry
 * Date: 3/13/2018
 * Time: 9:47 AM
 */


use yii\helpers\Html;


use common\components\Functions;
use common\models\procurement\Purchaseorderdetails;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\bootstrap\Modal;
use common\modules\pdfprint;


$func = new Functions();


$BaseURL = $GLOBALS['frontend_base_uri'];
$this->title = 'Inspection and Acceptance';
$angularcontroller = "";
$this->params['breadcrumbs'][] = '';
//$this->registerJsFile($BaseURL.'js/angular.min.js');
//$this->registerJsFile($BaseURL.'js/ui-bootstrap-tpls-0.10.0.min.js');
//$this->registerJsFile($BaseURL.'js/app.js');
$this->registerJsFile($BaseURL.'js/angular.min.js');
$this->registerJsFile($BaseURL.'js/ui-bootstrap-tpls-0.10.0.min.js');
$this->registerJsFile($BaseURL.'js/app.js');
$this->registerJsFile($BaseURL.'js/jquery.tabletojson.js');
$this->registerJsFile($BaseURL.'js/procurement/purchaseorder/purchaseorder.js');
$this->registerJsFile($BaseURL.'js/custom.js');

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
    echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
    Modal::end();
    ?>



    <!-- content -->
    <?php

    function filter($item) {
        $pofilter = Yii::$app->request->getQueryParam('filterpo', '');
        if (strlen($pofilter) > 0) {
            if (strpos($item['purchase_order_number'], $pofilter) != false) {
                return true;
            } else {
                return false;
            }
        } else {
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
            'attributes' => ['purchase_order_number'],
        ],
    ]);
    $pofilter = Yii::$app->request->getQueryParam('filterpo', '');
    $searchModel = ['purchase_order_number' => $pofilter];

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
            'width'=>'10%',
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
            'contentOptions' => [
                'style'=>'max-width:200px; overflow: auto; white-space: normal; word-wrap: break-word;'
            ],
            'width'=>'50%',
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
            'class' => 'kartik\grid\CheckboxColumn',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'checkboxOptions' => function($model, $key, $index, $column) {
                    return ['checked' => $model["delivered"],'value'=> $model["purchase_order_details_id"]];

            },
        ],


        [

            'label'=>'Actions',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'group'=>true,  // enable grouping
            'subGroupOf'=>1, // supplier column index is the parent group
            'format'=>'raw',
            'value' => function ($data) use ($func) {
                return Html::a('<span class="glyphicon glyphicon-print"></span>', ['reportpo?id='.$data["purchase_order_number"]], [
                    'class'=>'btn-pdfprint btn btn-warning',
                    'data-pjax'=>"0",
                    'pjax'=>"0",
                    'title'=>'Will open the generated PDF file in a new window'
                ]);
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


