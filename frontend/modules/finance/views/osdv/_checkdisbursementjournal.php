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

?>

<?= GridView::widget([
                'id' => 'request-obligation',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'showPageSummary' => true,
                //'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
                'columns' => [
                            /*[
                                'attribute'=>'request_date',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'width'=>'250px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return date('Y-m-d', strtotime($model->request_date));
                                },
                                'hAlign' => GridView::ALIGN_CENTER,
                                'filterType' => GridView::FILTER_DATE_RANGE,
                                    'value' => function($model) {
                                        if ($model->request_date) {
                                            return date('Y-m-d', strtotime($model->request_date));
                                        }
                                        return null;
                                    },
                                    'filterWidgetOptions' => [
                                        'startAttribute' => 'request_date_s', //Attribute of start time
                                        'endAttribute' => 'request_date_e',   //The attributes of the end time
                                        'convertFormat'=>true, // Importantly, true uses the local - > format time format to convert PHP time format to js time format.
                                        'pluginOptions' => [
                                            'format' => 'yyyy-mm-dd',//Date format
                                            //'timePicker'=>true, //Display time
                                            //'timePicker24Hour' => true, //24 hour system
                                            'locale'=>['format' => 'Y-m-d'], //php formatting time
                                            'opens'=>'left',
                                        ]
                                    ],
                            ],
                            [
                                'attribute'=>'payment_date',
                                'header'=>'Date',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'width'=>'250px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return date('Y-m-d', strtotime($model->osdv->lddapadaitem->lddapada->batch_date));
                                    //return date('Y-m-d', strtotime($model->request_date));
                                },
                                //'filterType' => $DateRangePicker(),
                                'hAlign' => GridView::ALIGN_CENTER,
                                'filterType' => GridView::FILTER_DATE_RANGE,
                                    'value' => function($model) {
                                        //if ($model->request_date) {
                                            return isset($model->osdv->lddapadaitem) ? date('Y-m-d', strtotime($model->osdv->lddapadaitem->lddapada->batch_date)) : '';
                                        //}
                                        //return null;
                                    },
                                    'filterWidgetOptions' => [
                                        'startAttribute' => 'request_date_s', //Attribute of start time
                                        'endAttribute' => 'request_date_e',   //The attributes of the end time
                                        'convertFormat'=>true, // Importantly, true uses the local - > format time format to convert PHP time format to js time format.
                                        'pluginOptions' => [
                                            'format' => 'yyyy-mm-dd',//Date format
                                            //'timePicker'=>true, //Display time
                                            //'timePicker24Hour' => true, //24 hour system
                                            'locale'=>['format' => 'Y-m-d'], //php formatting time
                                            'opens'=>'left',
                                        ]
                                    ],
                            ],*/
                            [
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
                            ],
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
    ?>

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