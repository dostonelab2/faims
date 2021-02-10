<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

use yii\widgets\ActiveForm;
use common\models\procurementplan\Ppmp;
use yii\helpers\ArrayHelper;
use kartik\editable\Editable;



/* @var $this yii\web\View */
/* @var $searchModel common\models\procurementplan\AppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Annual Procurement Plan';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="ppmpitem-index">
    <?php
    //print_r($_GET);
    //echo $_GET['AppSearch']['selectyear'];

    $columns = [
        [
            'class' => 'kartik\grid\SerialColumn',
            'contentOptions' => ['class' => 'kartik-sheet-style'],
            'width' => '20px',
            'header' => '#',
            'headerOptions' => [
                'class' => 'kartik-sheet-style',
                'style' => 'text-align: left; background-color: #7e9fda;'
            ],
            //'mergeHeader' => true,
        ],
        [
            'attribute' => 'availability',
            'header' => 'Category',
            'visible' =>  $dataProvider->totalCount > 0 ? true : false,
            'value' => function ($model, $key, $index, $widget) {
                if ($model->availability == 1) {
                    return 'PART I. AVAILABLE AT PROCUREMENT SERVICE STORES';
                } elseif ($model->availability == 2) {
                    return 'PART II. OTHER ITEMS NOT AVAILABLE AT PS BUT REGULARLY PURCHASED FROM OTHER SOURCES (Note: Please indicate price of items)';
                }
            },
            'headerOptions' => ['style' => 'background-color: #fee082;'],
            'contentOptions' => ['style' => 'background-color: #fee082; font-weight: bold;'],

            'group' => true,  // enable grouping,
            'groupedRow' => true,                    // move grouped column to a single grouped row
            //'contentOptions' => ['style' => 'text-align: left; background-color: #ffe699;'],

            'groupOddCssClass' => '',  // configure odd group cell css class
            'groupEvenCssClass' => '', // configure even group cell css class
        ],
        [
            'attribute' => 'item_category_id',
            'header' => 'Category',
            'visible' =>  $dataProvider->totalCount > 0 ? true : false,
            'width' => '100px',
            'value' => function ($model, $key, $index, $widget) {
                return $model->itemcategory->category_name;
            },
            'headerOptions' => ['style' => 'text-align: left; background-color: #7e9fda;'],
            'contentOptions' => ['style' => 'text-align: left; background-color: #7e9fda;'],

            'group' => true,  // enable grouping,
            'groupedRow' => true,                    // move grouped column to a single grouped row
            'groupOddCssClass' => '',  // configure odd group cell css class
            'groupEvenCssClass' => '', // configure even group cell css class
        ],
        [
            'attribute' => 'description',
            'header' => 'Items & Specification',
            'width' => '650px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: left;'],
            //'mergeHeader' => true,
        ],
        [
            'attribute' => 'unit',
            'header' => 'Unit of Measure',
            'value' => function ($model, $key, $index, $widget) {
                return $model->unitofmeasure->name;
            },
            'width' => '100px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: center'],
            //'mergeHeader' => true,
        ],
        [
            'attribute' => 'jan',
            'header' => 'Jan',
            'width' => '100px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right'],
        ],
        [
            'attribute' => 'feb',
            'header' => 'Feb',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right'],
        ],
        [
            'attribute' => 'mar',
            'header' => 'Mar',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right'],
        ],
        [
            'attribute' => 'q1',
            'header' => 'Q1',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right; background-color: #ededed'],
        ],
        [
            'attribute' => 'q1amount',
            'header' => 'Q1 AMOUNT',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right; background-color: #ededed; font-weight: bold;'],
        ],
        [
            'attribute' => 'apr',
            'header' => 'Apr',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right'],
        ],
        [
            'attribute' => 'may',
            'header' => 'May',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right'],
        ],
        [
            'attribute' => 'jun',
            'header' => 'Jun',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right'],
        ],
        [
            'attribute' => 'q2',
            'header' => 'Q2',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right; background-color: #ededed'],
        ],
        [
            'attribute' => 'q2amount',
            'header' => 'Q2 AMOUNT',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right; background-color: #ededed; font-weight: bold;'],
        ],
        [
            'attribute' => 'jul',
            'header' => 'Jul',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right'],
        ],
        [
            'attribute' => 'aug',
            'header' => 'Aug',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right'],
        ],
        [
            'attribute' => 'sep',
            'header' => 'Sep',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right'],
        ],
        [
            'attribute' => 'q3',
            'header' => 'Q3',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right; background-color: #ededed'],
        ],
        [
            'attribute' => 'q3amount',
            'header' => 'Q3 AMOUNT',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right; background-color: #ededed; font-weight: bold;'],
        ],
        [
            'attribute' => 'oct',
            'header' => 'Oct',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right'],
        ],
        [
            'attribute' => 'nov',
            'header' => 'Nov',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right'],
        ],
        [
            'attribute' => 'dec',
            'header' => 'Dec',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right'],
        ],
        [
            'attribute' => 'q4',
            'header' => 'Q4',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right; background-color: #ededed'],
        ],
        [
            'attribute' => 'q4amount',
            'header' => 'Q4 AMOUNT',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right; background-color: #ededed; font-weight: bold;'],
        ],
        [
            'attribute' => 'quantity',
            'header' => 'Total Quantity for the year',
            'width' => '75px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: center;'],
        ],
        [
            'attribute' => 'cost',
            'header' => 'Price Catalogue',
            'width' => '100px',
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right'],
            'pageSummary' => 'TOTAL'
        ],
        [
            'attribute' => 'totalamount',
            'header' => 'Total Amount for the year',
            'width' => '75px',
            'mergeHeader' => true,
            'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78; color: black'],
            'contentOptions' => ['style' => 'text-align: right; background-color: #ededed; font-weight: bold;'],
            'format' => ['decimal', 2],
            'pageSummary' => true
        ],
    ];

    ?>
    <?php $selectyear = $this->render('_selectyear', ['model' => $searchModel]); ?>
    <?php
    $exporbtn = Html::button(
        'Export to Excel',
        [
            'title' => 'Export',
            'value' => Url::to(['app/exporttoexcel']),
            'class' => 'btn btn-primary btnexport',
            'style' => 'width: 110px; margin-right: 6px;',
            'id' => 'btnExport'
        ]
    );
    /*
    $exporbtn = Html::a(
        'Export to Excel',
        ['app/exporttoexcel', 'year' => isset($_GET['AppSearch']['selectyear']) ? $_GET['AppSearch']['selectyear'] : ''],
        [
            'title' => 'Export',
            //'value' => Url::to(['app/exporttoexcel']),
            'class' => 'btn btn-primary btnexport',
            'style' => 'width: 110px; margin-right: 6px;',
            'id' => 'btnExport'
        ]
    );*/
    ?>

    <div class="panel panel-primary">
        <div class="panel-heading">Annual Procurement Mangement Plan</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $exporbtn; ?>
                </div>
                <div class="col-md-6">
                    <div class="pull-right">
                        <?= $selectyear; ?>
                    </div>
                </div>
                <div class=col-md-12>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        //'filterModel' => $searchModel,
                        'pjax' => true, // pjax is set to always true for this demo
                        'pjaxSettings' => [
                            'options' => [
                                'enablePushState' => false,
                                'id' => 'appgrid',
                                'timeout' => 1000,
                                'clientOptions' => ['backdrop' => false]
                            ],
                        ],
                        'showPageSummary' => true,
                        'summary' => false,
                        'columns' => $columns,
                        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                        'pjax' => true, // pjax is set to always true for this demo
                        // set left panel buttons
                        /*'panel' => [
            'heading' => '<h3 class="panel-title">Annual Procurement Plan</h3>',
            'type' => 'primary',
            'before'=> ''

        ],
        'toolbar' => [
            'content' => $selectyear,
        ],*/
                        'export' => [
                            'fontAwesome' => true
                        ],
                        'persistResize' => false,
                        'toggleDataOptions' => ['minCount' => 10],
                        //'exportConfig' => $exportConfig,
                        'itemLabelSingle' => 'item',
                        'itemLabelPlural' => 'items'
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!--export modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center">
            <div class="modal-content">
                <div class="modal-body text-primary" style="text-align:center">
                    <span class="fa fa-spinner fa-spin fa-2x"></span><strong style="font-size:25px">&nbsp;&nbsp;EXPORTING...</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .modal {}

    .vertical-alignment-helper {
        display: table;
        height: 100%;
        width: 100%;
    }

    .vertical-align-center {
        /* To center vertically */
        display: table-cell;
        vertical-align: middle;
    }

    .modal-content {
        /* Bootstrap sets the size of the modal in the modal-dialog class, we need to inherit it */
        /*background-color: transparent;*/
        border-radius: 25px;
        width: inherit;
        height: inherit;
        /* To center horizontally */
        margin: 0 auto;
    }

    .modal-dialog {
        width: 240px;
    }
</style>

<script>
    //script for button clicl for export to excel
    $("body").on("click", "#btnExport", function() {
        year = $("#cboYear").val();
        url = $("#btnExport").val();
        $("#myModal").modal('show');
        $.ajax({
            type: "GET",
            url: url,
            data: {year:year},
            success: function(data){
                $("#myModal").modal('hide');
                window.location.href = '/templates/APP-CES_2020_FORM.xls';
            }
        });
    }); 
</script>
