<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use common\modules\pdfprint;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\PurchaseorderdetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Purchase Order';
$this->params['breadcrumbs'][] = $this->title;

$BaseURL = $GLOBALS['frontend_base_uri'];
$this->registerJsFile($BaseURL . 'js/procurement/purchaseorder/purchaseorder.js');
$this->registerJsFile($BaseURL . 'js/custom.js');
$this->registerJsFile($BaseURL . 'js/sweetalert.min.js');

Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalPurchaseOrder',
    'size' => 'modal-lg',
    'options' => [
        'tabindex' => false,
    ]
]);
echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'>

    </div></div>";
Modal::end();
?>
<div class="purchaseorderdetails-index">
    <!-- 
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <p>
        <?= Html::a('Create Purchaseorderdetails', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'columns' => [
            [
                'class' => 'kartik\grid\SerialColumn',
                //'contentOptions' => ['class' => 'kartik-sheet-style'],
                'width' => '5%',
                'vAlign' => 'top',
                'header' => '',
                //'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],

            [
                'attribute' => 'purchaseordernumber',
                'contentOptions' => ['style' => 'max-width: 100px;'],
                // 'value' => function ($model, $key, $index, $widget) { 
                //     return $model->purchaseordernumber;
                // },
                'contentOptions' => [
                    'style' => 'max-width:70px; overflow: auto; white-space: normal; word-wrap: break-word;'
                ],
                'width' => '10%',
                'group' => true,  // enable grouping
            ],
            [
                'attribute' => 'suppliername',
                'group' => true,  // enable grouping
                'subGroupOf' => 1,
                'contentOptions' => [
                    'style' => 'max-width:110px; overflow: auto; white-space: normal; word-wrap: break-word;'
                ],
                'width' => '15%',
            ],
            [
                'label' => 'Unit',
                'width' => '15px',
                'value' => function ($model) {
                    if ($model->bidsdetails->purchaserequestdetail) {
                        return $model->bidsdetails->purchaserequestdetail->unittype->name_short;
                    } else {
                        return '';
                    }
                }
            ],
            [
                'attribute' => 'itemdescription',
                'format' => 'HTML',
                'contentOptions' => [
                    'style' => 'max-width:200px; overflow: auto; white-space: normal; word-wrap: break-word;'
                ],
                'width' => '35%',
                //'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],
            [
                'attribute' => 'Quantity',
                'width' => '15px',
                'value' => function ($model) {
                    return $model->bidsdetails->bids_quantity;
                }
            ],
            [
                'attribute' => 'Price',
                'width' => '15px',
                'value' => function ($model) {
                    return $model->bidsdetails->bids_price;
                }
            ],
            [
                'label' => 'Status',
                //'headerOptions' => ['class' => 'kartik-sheet-style'],
                'group' => true,  // enable grouping
                'subGroupOf' => 1, // supplier column index is the parent group
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->purchaseorder->purchase_order_status == 2) {
                        return '<span class="badge" style="background:#FF0000;">Canceled <i class="fa fa-remove"></i></span>';
                    }
                    if ($model->purchaseorder->purchase_order_status == 1) {
                        return '<span class="badge" style="background:#005cf0;">Active <i class="fa fa-check"></i></span>';
                    }
                    // if ($data['purchase_order_status'] == 2){
                    //     return '<span class="badge" style="background:#FF0000;">Canceled <i class="fa fa-remove"></i></span>';
                    // } 
                    // if ($data['purchase_order_status'] == 1){
                    //     return '<span class="badge" style="background:#005cf0;">Active <i class="fa fa-check"></i></span>';
                    // }    
                },
            ],

            // ['class' => 'kartik\grid\ActionColumn'],
            [
                'label' => 'Action',
                'group' => true,  // enable grouping
                'subGroupOf' => 1, // supplier column index is the parent group
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->purchaseorder->purchase_order_status == 1) {
                        return
                            Html::button(
                                '<span class=\'glyphicon glyphicon-pencil\'></span>',
                                [
                                    'value' => Url::to(['/procurement/purchaseorder/viewpo?id=' . $model->bidsdetails->bids_details_id . '&&' . 'mid=' . $model->purchase_order_id]),
                                    'title' => 'Modify Purchase Order', 'tab-index' => 0, 'class' => 'btn btn-success', 'id' => 'buttonAddObligation'
                                ]
                            ) .
                            Html::a('<span class="glyphicon glyphicon-print"></span>', ['/procurement/purchaseorder/reportpofull?id=' . $model->Purchaseordernumber . '&&' . 'mid=' . $model->purchase_order_id], [
                                'class' => 'btn-pdfprint btn btn-primary',
                                'data-pjax' => "0",
                                'pjax' => "0",
                                'title' => 'Will open the generated PDF file in a new window'
                            ]).
                            Html::button(
                                '<span class="glyphicon glyphicon-remove-sign"></span>',
                                [
                                    'title' => 'Cancel PO',
                                    'value' => $model->Purchaseordernumber,
                                    'class' => 'btn btn-danger btncancelpo',
                                    'id' => 'btncancelpo'
                                ]
                            );
                    }else{
                        return
                            Html::button(
                                '<span class=\'glyphicon glyphicon-pencil\'></span>',
                                [
                                    'value' => Url::to(['/procurement/purchaseorder/viewpo?id=' . $model->bidsdetails->bids_details_id . '&&' . 'mid=' . $model->purchase_order_id]),
                                    'title' => 'Modify Purchase Order', 'tab-index' => 0, 'class' => 'btn btn-success', 'id' => 'buttonAddObligation'
                                ]
                            );
                    }
                }
                //'class' => 'kartik\grid\ActionColumn'
            ]
        ],
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'toolbar' =>  [
            [
                'content' => ''
            ],
            '{export}',
            '{toggleData}',
        ],
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
            'heading' => '<b>' . $this->title . '</b>',
            'type' => 'primary',
        ],
        'persistResize' => false,
        'toggleDataOptions' => ['minCount' => 10],
        'exportConfig' => true,
    ]); ?>
    <?= pdfprint\Pdfprint::widget([
        'elementClass' => '.btn-pdfprint'
    ]); ?>
</div>