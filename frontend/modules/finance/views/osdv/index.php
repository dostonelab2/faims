<?php
use kartik\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

use kartik\datecontrol\DateControl;
use kartik\detail\DetailView;
use kartik\editable\Editable; 
use kartik\grid\GridView;

use yii\bootstrap\Modal;

use common\models\cashier\Creditor;
use common\models\finance\Dv;
use common\models\finance\Os;
use common\models\finance\Request;
use common\models\finance\Requestdistrict;
use common\models\finance\Requeststatus;
use common\models\system\Profile;
use common\models\system\Usersection;
use common\models\sec\Blockchain;
/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Obligation and Disbursement';
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

// Modal Create New Creditor
Modal::begin([
    'header' => '<h4 id="modalCreditorHeader" style="color: #ffffff"></h4>',
    'id' => 'modalCreditor',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();
?>

<div class="request-index">

    <h1><?= Html::encode($this->title) ?></h1>
<?php Pjax::begin(); ?>
      <?php
        echo GridView::widget([
            'id' => 'request',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'columns' => [
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
                                    //return isset($model->osdv->dv) ? '<b>'.$model->osdv->dv->dv_number.'</b><br/>'.date('Y-m-d', strtotime($model->osdv->dv->dv_date)) : '';
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Dv::find()->orderBy(['dv_id' => SORT_DESC])->asArray()->all(), 'dv_id', 'dv_number'), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],  
                                'filterInputOptions' => ['placeholder' => 'Select DV'],
                            ],
                            [
                                'attribute'=>'request_number',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: center;'],
                                'width'=>'120px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return '<b>'.$model->request_number.'</b><br/>'.date('Y-m-d', strtotime($model->request_date));
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Request::find()->where(['>', 'status_id', 40])->orderBy(['request_id' => SORT_DESC])->asArray()->all(), 'request_id', 'request_number'), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],  
                                'filterInputOptions' => ['placeholder' => 'Select Request'],
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
                            [
                                'attribute'=>'amount',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: right; padding-right: 20px; font-weight: bold;'],
                                'width'=>'200px',
                                'format' => ['decimal', 2],
                                'value'=>function ($model, $key, $index, $widget) { 
                                    //$fmt = Yii::$app->formatter;
                                    return $model->amount;
                                },
                            ],
                            [
                                'attribute'=>'status_id',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center; vertical-align:middle;'],
                                'width'=>'250px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return '<span class="label label-info">'.$model->status->name.'</span>';
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Requeststatus::find()->where(['>', 'request_status_id', 40])->asArray()->all(), 'request_status_id', 'name'), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],  
                                'filterInputOptions' => ['placeholder' => 'Select Status'],
                            ],
                            /*[
                                'attribute'=>'created_by',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center; vertical-align:middle; '],
                                'width'=>'250px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    //return Profile::find($model->created_by)->one()->fullname;
                                    return $model->profile->fullname;
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Profile::find()->asArray()->all(), 'profile_id', 
                                                                function($model) {
                                                                    return $model['firstname'].' '.$model['lastname'];
                                                                }
                                                            ), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],  
                                'filterInputOptions' => ['placeholder' => 'Created by'],
                            ],*/
                            [
                                'class' => kartik\grid\ActionColumn::className(),
                                //'class' => kartik\grid\ActionColumn::className(),
                                'template' => '{view}{printos}{printdv}',
                                'headerOptions' => ['style' => 'background-color: #fff;'],
                                'buttons' => [
                                    'view' => function ($url, $model){
                                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value' => '/finance/osdv/view?id=' . $model->osdv->osdv_id,'onclick'=>'location.href=this.value', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View Request")]);
                                    },
                                    'printos' => function ($url, $model){
                                        return $model->osdv->isObligated() ? Html::button('<span class="glyphicon glyphicon-print"></span>', ['value' => '/finance/request/printos?id=' . $model->request_id,'onclick'=>'window.open(this.value)', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "Print OS")]) : '';
                                    },
                                    'printdv' => function ($url, $model){
                                        return $model->osdv->isCharged() ? Html::button('<span class="glyphicon glyphicon-print"></span>', ['value' => '/finance/request/printdv?id=' . $model->request_id,  'onclick'=>'window.open(this.value)', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "Print DV"), 'target' => '_blank']) : '';
                                    },
                                ],
                            ],
                            /*[
                                'class' => kartik\grid\ActionColumn::className(),
                                'template' => '{view}',
                                'buttons' => [

                                    'view' => function ($url, $model){
                                        return $model->osdv ? Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value' => '/finance/osdv/view?id=' . $model->osdv->osdv_id,'onclick'=>'location.href=this.value', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View Request")]) : '';
                                    },
                                ],
                            ],*/
                    ],
            
            'pjax' => true, // pjax is set to always true for this demo
            'panel' => [
                    'heading' => '',
                    'type' => GridView::TYPE_PRIMARY,
                    'before'=>Html::button('Validated Requests  &nbsp;&nbsp;<span class="badge badge-light">'.$numberOfRequests.'</span>', ['value' => Url::to(['osdv/create']), 'title' => 'Request', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonCreateOsdv']),
                    'after'=>false,
                ],
            // set your toolbar
            'toolbar' => 
                        [
                            [
                                'content'=>'',
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