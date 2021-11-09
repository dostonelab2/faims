<?php
use kartik\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

use kartik\form\ActiveForm;

use kartik\datecontrol\DateControl;
use kartik\daterange\DateRangePicker;
use kartik\detail\DetailView;
use kartik\editable\Editable; 
use kartik\grid\GridView;

use yii\bootstrap\Modal;

use common\models\cashier\Creditor;
use common\models\finance\Dv;
use common\models\finance\Obligationtype;
use common\models\finance\Os;
use common\models\finance\Request;
use common\models\system\Profile;
/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Check Disbursement Journal';
$this->params['breadcrumbs'][] = $this->title;

// Modal Create Request
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalRequest',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

$gridColumns = [
        [
            'attribute' => 'supplier_id', 
            'headerOptions' => ['style' => 'background-color: #fee082;'],
            'contentOptions'=>['style'=>'background-color: #fee082; font-weight: bold;'],
            'width' => '310px',
            'value' => function ($model, $key, $index, $widget) { 
                return Creditor::findOne($model->osdv->lddapadaitem->creditor_id)->name;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(Creditor::find()->asArray()->all(), 'creditor_id', 
                                                                function($model) {
                                                                    return $model['name'].' | '.$model['address'];
                                                                }
                                                            ), 
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Search Creditor'],
            'contentOptions'=>['style'=>'background-color: #fee082; font-weight: bold;'],
            'group' => true,  // enable grouping,
            'groupedRow' => true,                    // move grouped column to a single grouped row
            'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
            'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
            'groupFooter' => function ($model, $key, $index, $widget) { // Closure method
                return [
                    'mergeColumns' => [[1,9]], // columns to merge in summary
                    'content' => [             // content to show in each summary cell
                        1 => 'Summary',
                        //1 => 'Summary (' . Creditor::findOne($model->osdv->lddapadaitem->creditor_id)->name . ')',
                        //4 => GridView::F_AVG,
                        //5 => GridView::F_SUM,
                        10 => GridView::F_SUM,
                    ],
                    'contentFormats' => [      // content reformatting for each summary cell
                        //4 => ['format' => 'number', 'decimals' => 2],
                        //5 => ['format' => 'number', 'decimals' => 0],
                        10 => ['format' => 'number', 'decimals' => 2],
                    ],
                    'contentOptions' => [      // content html attributes for each summary cell
                        0 => ['style' => 'font-variant:small-caps'],
                        //4 => ['style' => 'text-align:right'],
                        //5 => ['style' => 'text-align:right'],
                        10 => ['style' => 'text-align:right'],
                    ],
                    // html attributes for group summary row
                    'options' => ['class' => 'info table-info','style' => 'font-weight:bold; text-align: right;']
                ];
            }
        ],
        [
            'class' => 'kartik\grid\SerialColumn',
            //'headerOptions' => ['style' => 'display: none;'],
        ],
        [
            'attribute' => 'os_id',
            'header'=>'ORS NO.',
            'headerOptions' => ['style' => 'text-align: center;'],
            'width' => '150px',
            'hAlign' => 'center',
            'format'=>'raw',
            'value'=>function ($model, $key, $index, $widget) { 
                return (isset($model->osdv->os) ? $model->osdv->os->os_number : '');
            },
            'group' => true,  // enable grouping
            'subGroupOf' => 1, // supplier column index is the parent group,
        ],
        [
            'attribute' => 'dv_id',
            'header'=>'JEV NO.',
            'headerOptions' => ['style' => 'text-align: center;'],
            'width' => '150px',
            'hAlign' => 'center',
            'format'=>'raw',
            'value'=>function ($model, $key, $index, $widget) { 
                return (isset($model->osdv->dv) ? $model->osdv->dv->dv_number : '');
            },
            'group' => true,  // enable grouping
            'subGroupOf' => 1, // supplier column index is the parent group,
        ],
        [
            'attribute' => 'taxable',
            'header'=>'CHECK No.',
            'headerOptions' => ['style' => 'text-align: center;'],
            'width' => '150px',
            'hAlign' => 'center',
            'format'=>'raw',
            'value'=>function ($model, $key, $index, $widget) { 
                return 'CHECK Number';
                //return (isset($model->osdv->dv) ? $model->osdv->dv->dv_number : '');
            },
            'group' => true,  // enable grouping
            'subGroupOf' => 1, // supplier column index is the parent group,
        ],
        [
            'attribute' => 'dv_id',
            'header'=>'1-01-04-040',
            'headerOptions' => ['style' => 'text-align: center;'],
            'width' => '150px',
            'hAlign' => 'center',
            'format'=>'raw',
            'value'=>function ($model, $key, $index, $widget) { 
                return ( ($model->debitcreditflag == 2) && ($model->account_id != 31) ) ? $model->amount : '0.00';
            },
        ],
        [
            'attribute'=>'gross_amount',
            'header'=>'2-02-01-010',
            'headerOptions' => ['style' => 'text-align: center;'],
            'contentOptions' => ['style' => 'text-align: right; padding-right: 25px;'],
            'width'=>'250px',
            'format'=>['decimal',2],
            'value'=>function ($model, $key, $index, $widget) { 
                return ( ($model->debitcreditflag == 2) && ($model->account_id == 31) ) ? $model->amount : '0.00';
            },
            'xlFormat'=>'0\.00E+00', // scientific
            'pageSummary'=>true,
        ],
    
    
        [
            'attribute' => 'dv_id',
            'header'=>'UACS Code (Sundry)',
            'headerOptions' => ['style' => 'text-align: center;'],
            'width' => '150px',
            'hAlign' => 'center',
            'format'=>'raw',
            'value'=>function ($model, $key, $index, $widget) { 
                return '';
            },
            'group' => true,  // enable grouping
            'subGroupOf' => 1, // supplier column index is the parent group,
        ],
        [
            'attribute'=>'gross_amount',
            'header'=>'Amount (Sundry)',
            'headerOptions' => ['style' => 'text-align: center;'],
            'contentOptions' => ['style' => 'text-align: right; padding-right: 25px;'],
            'width'=>'150px',
            'format'=>['decimal',2],
            'value'=>function ($model, $key, $index, $widget) {
                return '';
            },
            'xlFormat'=>'0\.00E+00', // scientific
            'pageSummary'=>true
        ],
        [
            'attribute'=>'expenditure_object_id',
            'header'=>'UACS Code',
            'headerOptions' => ['style' => 'text-align: center;'],
            'contentOptions' => ['style' => 'padding-left: 25px; font-weigth: bold;'],
            'width'=>'800px',
            'contentOptions' => [
                'style'=>'max-width:300px; overflow: auto; white-space: normal; word-wrap: break-word;'
            ],
            //'format' => 'raw',
            'value'=>function ($model, $key, $index, $widget) { 
                return $model->osdv->uacs ? $model->osdv->uacs->expenditureobject->object_code : '-';
            },
        ],
        [
            'attribute'=>'gross_amount',
            'header'=>'Gross Amount',
            'headerOptions' => ['style' => 'text-align: center;'],
            'contentOptions' => ['style' => 'text-align: right; padding-right: 25px;'],
            'width'=>'150px',
            'format'=>['decimal',2],
            'value'=>function ($model, $key, $index, $widget) {
                return ($model->debitcreditflag == 1) ? $model->amount : '0.00';
            },
            'xlFormat'=>'0\.00E+00', // scientific
            'pageSummary'=>true
        ],
    ];

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'showPageSummary' => true,
    'pjax' => true,
    'striped' => false,
    'hover' => true,
    'panel' => ['type' => 'primary', 'heading' => 'Check Disbursement Journal'],
    'toggleDataContainer' => ['class' => 'btn-group mr-2 me-2'],
    /*'beforeHeader' => [
    [
        'columns' => [ 
                    ['content' => '#', 'options' => ['colspan' => 1, 'rowspan' => 3,'style' => 'text-align: center; vertical-align: middle;']],
                    ['content' => 'ORS NO.', 'options' => ['colspan' => 1, 'rowspan' => 3,'style' => 'text-align: center; vertical-align: middle;']],
                    ['content' => 'JEV NO.', 'options' => ['colspan' => 1, 'rowspan' => 3,'style' => 'text-align: center; vertical-align: middle;']],
                    ['content' => 'Check No.', 'options' => ['colspan' => 1, 'rowspan' => 3,'style' => 'text-align: center; vertical-align: middle;']],
                    ['content' => 'CREDIT', 'options' => ['colspan' => 4, 'style' => 'text-align: center; vertical-align: middle;']],
                    ['content' => 'DEBIT', 'options' => ['colspan' => 2, 'style' => 'text-align: center; vertical-align: middle;']],
            ],
        ]
    ],*/
    'columns' => $gridColumns
]);


?>

<!--?= GridView::widget([
                'id' => 'request-obligation',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'showPageSummary' => true,
                //'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
                'columns' => [
                            [
                                'attribute'=>'os_id',
                                'header'=>'OS Number',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: center;'],
                                'width'=>'220px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return (isset($model->osdv->os) ? '<span class="label label-info">'.$model->osdv->os->os_number.'</span>' : '');
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Os::find()->orderBy(['os_id' => SORT_DESC])->asArray()->all(), 'os_id', 'os_number'), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],  
                                'filterInputOptions' => ['placeholder' => 'Select OS'],
                                
                                'groupedRow' => true,                    // move grouped column to a single grouped row
                                'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
                                'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
                            ],
                            [
                                'attribute'=>'dv_id',
                                'header'=>'DV Number',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: center;'],
                                'width'=>'220px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return (isset($model->osdv->dv) ? '<span class="label label-info">'.$model->osdv->dv->dv_number.'</span>' : '');
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Dv::find()->orderBy(['dv_id' => SORT_DESC])->asArray()->all(), 'dv_id', 'dv_number'), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],  
                                'filterInputOptions' => ['placeholder' => 'Select DV'],
                            ],
                            [
                                'attribute'=>'creditor_id',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'padding-left: 25px; font-weigth: bold;'],
                                'width'=>'800px',
                                'contentOptions' => [
                                    'style'=>'max-width:300px; overflow: auto; white-space: normal; word-wrap: break-word;'
                                ],
                                'format' => 'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return Html::tag('span', '<b>'.Creditor::findOne($model->osdv->lddapadaitem->creditor_id)->name.'</b>', [
                                        //'title'=>'Created by: '.Profile::find($model->created_by)->one()->fullname,
                                        //'data-toggle'=>'tooltip',
                                        //'data-content'=>Profile::find($model->created_by)->one()->fullname,
                                        //'data-toggle'=>'popover',
                                        'style'=>'text-decoration: underline; cursor:pointer;'
                                    ]).'<br>';
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Creditor::find()->asArray()->all(), 'creditor_id', 
                                                                function($model) {
                                                                    return $model['name'].' | '.$model['address'];
                                                                }
                                                            ), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],  
                                'filterInputOptions' => ['placeholder' => 'Select Payee'],
                                
                                'group'=>true,  // enable grouping,
                                'groupedRow'=>true,                    // move grouped column to a single grouped row
                                //'contentOptions' => ['style' => 'text-align: left; background-color: #ffe699;'],

                                'groupOddCssClass'=>'',  // configure odd group cell css class
                                'groupEvenCssClass'=>'', // configure even group cell css class
                            ],
                            [
                                'attribute'=>'gross_amount',
                                'header'=>'1-01-04-040',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: right; padding-right: 25px;'],
                                'width'=>'150px',
                                'format'=>['decimal',2],
                                'value'=>function ($model, $key, $index, $widget) {
                                    return ( ($model->debitcreditflag == 2) && ($model->account_id != 31) ) ? $model->amount : '0.00';
                                },
                                'xlFormat'=>'0\.00E+00', // scientific
                                'pageSummary'=>true
                            ],
                            [
                                'attribute'=>'gross_amount',
                                'header'=>'2-02-01-010',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: right; padding-right: 25px;'],
                                'width'=>'250px',
                                'format'=>['decimal',2],
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return ( ($model->debitcreditflag == 2) && ($model->account_id == 31) ) ? $model->amount : '0.00';
                                },
                                'xlFormat'=>'0\.00E+00', // scientific
                                'pageSummary'=>true
                            ],
                            [
                                'attribute'=>'expenditure_object_id',
                                'header'=>'UACS Code',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'padding-left: 25px; font-weigth: bold;'],
                                'width'=>'800px',
                                'contentOptions' => [
                                    'style'=>'max-width:300px; overflow: auto; white-space: normal; word-wrap: break-word;'
                                ],
                                //'format' => 'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    //return $model->expenditureObject->object_code;
                                    return $model->osdv->uacs ? $model->osdv->uacs->expenditureobject->object_code : '-';
                                },
                            ],
                            [
                                'attribute'=>'gross_amount',
                                'header'=>'Gross Amount',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: right; padding-right: 25px;'],
                                'width'=>'150px',
                                'format'=>['decimal',2],
                                'value'=>function ($model, $key, $index, $widget) {
                                    return ($model->debitcreditflag == 1) ? $model->amount : '0.00';
                                },
                                'xlFormat'=>'0\.00E+00', // scientific
                                'pageSummary'=>true
                            ],
                            /*[
                                'attribute'=>'os_id',
                                'header'=>'OS Number',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: center;'],
                                'width'=>'220px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return (isset($model->osdv->os) ? '<span class="label label-info">'.$model->osdv->os->os_number.'</span><br/>'.$model->osdv->os->os_date : '');
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Os::find()->orderBy(['os_id' => SORT_DESC])->asArray()->all(), 'os_id', 'os_number'), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],  
                                'filterInputOptions' => ['placeholder' => 'Select OS'],
                            ],
                            [
                                'attribute'=>'dv_id',
                                'header'=>'DV Number',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: center;'],
                                'width'=>'220px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return (isset($model->osdv->dv) ? '<span class="label label-info">'.$model->osdv->dv->dv_number.'</span><br/>'.$model->osdv->dv->dv_date : '');
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Dv::find()->orderBy(['dv_id' => SORT_DESC])->asArray()->all(), 'dv_id', 'dv_number'), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],  
                                'filterInputOptions' => ['placeholder' => 'Select DV'],
                            ],
                            [
                                'attribute'=>'creditor_id',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'padding-left: 25px; font-weigth: bold;'],
                                'width'=>'800px',
                                'contentOptions' => [
                                    'style'=>'max-width:300px; overflow: auto; white-space: normal; word-wrap: break-word;'
                                ],
                                'format' => 'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return Html::tag('span', '<b>'.Creditor::findOne($model->creditor_id)->name.'</b>', [
                                        //'title'=>'Created by: '.Profile::find($model->created_by)->one()->fullname,
                                        //'data-toggle'=>'tooltip',
                                        //'data-content'=>Profile::find($model->created_by)->one()->fullname,
                                        //'data-toggle'=>'popover',
                                        'style'=>'text-decoration: underline; cursor:pointer;'
                                    ]).'<br>';
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Creditor::find()->asArray()->all(), 'creditor_id', 
                                                                function($model) {
                                                                    return $model['name'].' | '.$model['address'];
                                                                }
                                                            ), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],  
                                'filterInputOptions' => ['placeholder' => 'Select Payee']
                            ],
                            [
                                'attribute'=>'gross_amount',
                                'header'=>'1-01-04-040',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: right; padding-right: 25px;'],
                                'width'=>'150px',
                                'format'=>['decimal',2],
                                'value'=>function ($model, $key, $index, $widget) {
                                    return $model->osdv->getNetamount();
                                },
                                'xlFormat'=>'0\.00E+00', // scientific
                                'pageSummary'=>true
                            ],
                            [
                                'attribute'=>'gross_amount',
                                'header'=>'2-02-01-010',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: right; padding-right: 25px;'],
                                'width'=>'250px',
                                'format'=>['decimal',2],
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->osdv->getTax();
                                },
                                'xlFormat'=>'0\.00E+00', // scientific
                                'pageSummary'=>true
                            ],
                            [
                                'attribute'=>'expenditure_object_id',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'padding-left: 25px; font-weigth: bold;'],
                                'width'=>'800px',
                                'contentOptions' => [
                                    'style'=>'max-width:300px; overflow: auto; white-space: normal; word-wrap: break-word;'
                                ],
                                //'format' => 'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    //return $model->expenditureObject->object_code;
                                    return $model->osdv->uacs ? $model->osdv->uacs->expenditureobject->object_code : '-';
                                },
                            ],
                            [
                                'attribute'=>'gross_amount',
                                'header'=>'Gross Amount',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: right; padding-right: 25px;'],
                                'width'=>'150px',
                                'format'=>['decimal',2],
                                'value'=>function ($model, $key, $index, $widget) {
                                    return $model->osdv->getGrossamount();
                                },
                                'xlFormat'=>'0\.00E+00', // scientific
                                'pageSummary'=>true
                            ],*/
                    ],
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
                    'heading' => '<h3 class="panel-title">Report of Disbursement</h3>',
                    //'type' => GridView::TYPE_INFO,
                    'type' => GridView::TYPE_PRIMARY,
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
    ?-->

<style>
/****** date range picker CSS ******/
div.daterangepicker.ltr.show-calendar.opensleft{
    width: 685px !important;
}
.daterangepicker.ltr .drp-calendar.left {
    clear: left;
    margin-right: 65px !important;
}

.input-container {
  display: -ms-flexbox; /* IE10 */
  display: flex;
  width: 100%;
  /* margin-bottom: 15px; */
}

.icon {
  padding: 10px;
  border-radius: 10%;
  background: dodgerblue;
  color: white;
  min-width: 20px;
  text-align: center;
}
</style>