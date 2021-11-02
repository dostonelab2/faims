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
use common\models\finance\Os;
use common\models\finance\Request;
use common\models\system\Profile;
/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Report of Disbursement';
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

///echo '<span class="badge btn-success">'.$numberOfRequests.'</span>';

/****Date range picker *****/
$DateRangePicker = function(){
    $daterange =  DateRangePicker::widget([
        'name'=>'date_range_1',
        'value'=>'01-Jan-21 to 20-Feb-21',
        'options' => [
            'class' => 'form-control',
            'id' => 'date-range'
        ],
        'bsVersion' => '4.x',
        //'bsDependencyEnabled' => false,
        'convertFormat'=>true,
        'useWithAddon'=>true,
        'pluginOptions'=>[
            'locale'=>[
                'format'=>'d-M-y',
                'separator'=>' to ',
            ],
            'opens'=>'left'
        ]
    ]) . '<i class="fas fa-calendar-alt icon"></i>';

    return '<div class="input-group input-container drp-container">'. $daterange .'</div>';
};
?>
<div class="request-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <!--?= Html::a('Create', ['create'], ['class' => 'btn btn-success', 'id' => 'buttonCreateRequest']) ?-->
    </p>
<?php Pjax::begin(); ?>
      <?php
        echo GridView::widget([
            'id' => 'request',
            'filterModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'columns' => [
                            [
                                'attribute'=>'request_date',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'width'=>'150px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return date('Y-m-d', strtotime($model->request_date));
                                },
                                /*'filterType' => GridView::FILTER_DATE_RANGE,
                                    'value' => function($model) {
                                        if ($model->request_date) {
                                            return date('Y-m-d H:i:s',$model->request_date);
                                        }
                                        return null;
                                    },
                                    'filterWidgetOptions' => [
                                        'startAttribute' => 'request_date_c', //Attribute of start time
                                        'endAttribute' => 'request_date_e',   //The attributes of the end time
                                        'convertFormat'=>true, // Importantly, true uses the local - > format time format to convert PHP time format to js time format.
                                        'pluginOptions' => [
                                            'format' => 'yyyy-mm-dd hh:ii:ss',//Date format
                                              'timePicker'=>true, //Display time
                        //                        'Time Picker Increment'=>5, //min interval
                                             'timePicker24Hour' => true, //24 hour system
                                            'locale'=>['format' => 'Y-m-d H:i:s'], //php formatting time
                                        ]
                                    ],*/
                            ],
                            [
                                'attribute'=>'payee_id',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'padding-left: 25px; font-weigth: bold;'],
                                'width'=>'800px',
                                'contentOptions' => [
                                    'style'=>'max-width:300px; overflow: auto; white-space: normal; word-wrap: break-word;'
                                ],
                                'format' => 'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return Html::tag('span', '<b>'.Creditor::findOne($model->payee_id)->name.'</b>', [
                                        'title'=>'Created by: '.Profile::find($model->created_by)->one()->fullname,
                                        //'data-toggle'=>'tooltip',
                                        //'data-content'=>Profile::find($model->created_by)->one()->fullname,
                                        //'data-toggle'=>'popover',
                                        'style'=>'text-decoration: underline; cursor:pointer;'
                                    ]).'<br>' .$model->particulars;
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
                            /*[
                                'attribute'=>'payee_id',
                                'contentOptions' => ['style' => 'padding-left: 25px; font-weigth: bold;'],
                                'width'=>'550px',
                                'contentOptions' => [
                                    'style'=>'max-width:300px; overflow: auto; white-space: normal; word-wrap: break-word;'
                                ],
                                'format' => 'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return '<b>' . Creditor::findOne($model->payee_id)->name. '</b><br>' .$model->particulars;
                                },
                            ],
                            
                            [
                                'attribute'=>'osdv_id',
                                'header'=>'OS Number',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'width'=>'150px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return isset($model->os->os_id) ? '<b>'.$model->os->os_number.'</b>' : '';
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Os::find()->orderBy(['os_id' => SORT_DESC])->asArray()->all(), 'os_id', 'os_number'), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],  
                                'filterInputOptions' => ['placeholder' => 'Select OS'],
                            ],
                            [
                                'attribute'=>'osdv_id',
                                'header'=>'DV Number',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'width'=>'150px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return isset($model->dv->dv_id) ? '<b>'.$model->dv->dv_number.'</b>' : '';
                                    //return $model->dv->dv_number;
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Dv::find()->orderBy(['dv_id' => SORT_DESC])->asArray()->all(), 'dv_id', 'dv_number'), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],  
                                'filterInputOptions' => ['placeholder' => 'Select DV'],
                            ],*/
                            [
                                'attribute'=>'os_id',
                                'header'=>'OS Number',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: center;'],
                                'width'=>'220px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    switch ($model->status_id) {
                                      case ($model->status_id==50):
                                        $label = 'label-warning';
                                        break;
                                      case ($model->status_id==55):
                                        $label = 'label-success';
                                        break;
                                      case ($model->status_id>55):
                                        $label = 'label-info';
                                        break;
                                      default:
                                        $label = 'label-warning';
                                    }
                                    
                                    //return isset($model->osdv->os) ? '<b>'.$model->osdv->os->os_number.'</b><br/>'.date('Y-m-d', strtotime($model->osdv->os->os_date)) : '';
                                    return (isset($model->osdv->os) ? '<span class="label '.$label.'">'.$model->osdv->os->os_number.'</span><br/>'.$model->osdv->os->os_date : '');
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
                                    switch ($model->status_id) {
                                      case ($model->status_id==60):
                                        $label = 'label-warning';
                                        break;
                                      case ($model->status_id==65):
                                        $label = 'label-success';
                                        break;
                                      case ($model->status_id>65):
                                        $label = 'label-info';
                                        break;
                                      default:
                                        $label = 'label-warning';
                                            
                                    }
                                    return (isset($model->osdv->dv) ? '<span class="label '.$label.'">'.$model->osdv->dv->dv_number.'</span><br/>'.$model->osdv->dv->dv_date : '');
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Dv::find()->orderBy(['dv_id' => SORT_DESC])->asArray()->all(), 'dv_id', 'dv_number'), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],  
                                'filterInputOptions' => ['placeholder' => 'Select DV'],
                            ],
                            [
                                'attribute'=>'amount',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: right; padding-right: 25px;'],
                                'width'=>'150px',
                                'format'=>['decimal',2],
                                'value'=>function ($model, $key, $index, $widget) {
                                    //return $model->accounttransactions->taxable ? '0.00' : '<b>'.$model->request->amount.'</b>';
                                    return $model->osdv->getNetamount();
                                },
                            ],
                            [
                                'attribute'=>'amount',
                                'header'=>'Tax',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: right; padding-right: 25px;'],
                                'width'=>'250px',
                                'format'=>['decimal',2],
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->osdv->getTax();
                                    //return '<b>'.$model->request->amount.'</b>';
                                },
                            ],
                            [
                                'attribute'=>'amount',
                                'header'=>'Gross Amount',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: right; padding-right: 25px;'],
                                'width'=>'150px',
                                'format'=>['decimal',2],
                                'value'=>function ($model, $key, $index, $widget) {
                                    return $model->osdv->getGrossamount();
                                    //return '<b>'.$model->request->amount.'</b>';
                                },
                            ],
                    ],
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            /*'rowOptions' => function($model){
                switch ($model->status_id) {
                    case Request::STATUS_VALIDATED:
                        return ['class'=>'warning'];
                        break;
                    case Request::STATUS_CERTIFIED_ALLOTMENT_AVAILABLE:
                        return ['class'=>'warning'];
                        break;
                    case Request::STATUS_ALLOTTED:
                        return ['class'=>'warning'];
                        break;
                    case Request::STATUS_CERTIFIED_FUNDS_AVAILABLE:
                        return ['class'=>'warning'];
                        break;
                    case Request::STATUS_CHARGED:
                        return ['class'=>'warning'];
                        break;
                    case Request::STATUS_APPROVED_FOR_DISBURSEMENT:
                        return ['class'=>'success'];
                        break;
                }
                 
            },*/
            'panel' => [
                    'heading' => '',
                    'type' => GridView::TYPE_PRIMARY,
                    'before'=>Html::button('Validated Requests  &nbsp;&nbsp;<span class="badge badge-light"></span>', ['value' => Url::to(['osdv/create']), 'title' => 'Request', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonCreateOsdv']),/*.' '.
                    DateRangePicker::widget([
                        'model'=>$model,
                        'attribute'=>'datetime_range',
                        'convertFormat'=>true,
                        'pluginOptions'=>[
                            'timePicker'=>true,
                            'timePickerIncrement'=>30,
                            'format'=>'Y-m-d h:i A'
                        ]
                    ]),*/
                    'after'=>false,
                ],
            
            // set your toolbar
            'toolbar' => 
                        [
                            [
                                'content'=> $DateRangePicker() //Date Range Picker
                                    /*Html::button('PENDING', ['title' => 'Approved', 'class' => 'btn btn-warning', 'style'=>'width: 90px; margin-right: 6px;']) .    
                                    Html::button('SUBMITTED', ['title' => 'Approved', 'class' => 'btn btn-primary', 'style'=>'width: 90px; margin-right: 6px;']) .
                                    Html::button('APPROVED', ['title' => 'Approved', 'class' => 'btn btn-success', 'style'=>'width: 90px; margin-right: 6px;'])*/
                            ],
                            //'{export}',
                            //'{toggleData}'
                        ],
            
            'toggleDataOptions' => ['minCount' => 10],
            //'exportConfig' => $exportConfig,
            'itemLabelSingle' => 'item',
            'itemLabelPlural' => 'items'
        ]);
    

        ?>
        <?php Pjax::end(); ?>
</div>

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