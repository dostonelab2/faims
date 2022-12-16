<!--
<script type="text/javascript">
    $(function() {
        $(".knob").knob();
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Knob/1.2.13/jquery.knob.min.js"></script>
-->

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
use common\models\finance\Obligationtype;
use common\models\finance\Request;
use common\models\finance\Requestdistrict;
use common\models\finance\Requeststatus;
use common\models\procurement\Division;
use common\models\system\Profile;
use common\models\system\Usersection;
use common\models\sec\Blockchain;
/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Request';
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

// Modal Status
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalContainer',
    'size' => 'modal-lg',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();
?>

<div class="request-index">
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
                                'attribute'=>'request_number',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: center;'],
                                'width'=>'120px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return '<b>'.$model->request_number.'</b><br/>'.date('Y-m-d', strtotime($model->request_date));
                                },
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
                                    
                                    return (($model->payroll != 0) ? "" : Html::a(Creditor::findOne($model->payee_id)->name, ['request/view', 'id'=>$model->request_id], 
                                            ['style' => 'cursor:pointer; font-size: medium; font-weight: bold;', 'target' => '_blank', 'data-pjax'=>0])
                                        ).'<br>' .$model->particulars;

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
                                'attribute'=>'division_id',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center; vertical-align:middle;'],
                                'width'=>'100px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return '<span class="label label-success">'.$model->division->code.'</span>';
                                    
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Division::find()->asArray()->all(), 'division_id', 'code'), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],  
                                'filterInputOptions' => ['placeholder' => 'Select Division'],
                            ],
                            [
                                'attribute'=>'obligation_type_id',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center; vertical-align:middle;'],
                                'width'=>'250px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return '<span class="label label-info">'.$model->fundsource->name.'</span>';
                                    
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Obligationtype::find()->asArray()->all(), 'type_id', 'name'), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],  
                                'filterInputOptions' => ['placeholder' => 'Select Fund Source'],
                            ],
                            [
                                'attribute'=>'status_id',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center; vertical-align:middle;'],
                                'width'=>'250px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    //return '<span class="label label-info">'.($model->status ? $model->status->name : "").'</span>';
                                    //return Html::button($model->status ? $model->status->name : "", ['value' => Url::to(['request/tracking', 'id'=>$model->request_id]), 'title' => 'Track Request', 'class' => 'btn btn-sm btn-success', 'id'=>'buttonTrackProgress']);buttonUploadAttachments
                                    
                                    return Html::button('<i class="glyphicon glyphicon-time"></i> '.($model->status ? $model->status->name : ""), ['value' => Url::to(['request/tracking', 'id'=>$model->request_id]), 'title' => Yii::t('app', "Track Request"), 'class' => 'btn btn-md btn-success', 'id'=>'buttonUploadAttachments']);
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Requeststatus::find()->asArray()->all(), 'request_status_id', 'name'), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],  
                                'filterInputOptions' => ['placeholder' => 'Select Status'],
                            ],
                            /*[
                                'attribute'=>'status_id',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center; vertical-align:middle;'],
                                'width'=>'250px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    $status = ($model->status ? $model->status->name : "");
                                    
                                    return '
                                        <div class="col-xs-4 text-center"">
                                            <div style="display:inline;width:60px;height:60px;">
                                                <canvas width="70" height="70" style="width: 60px; height: 60px;"></canvas>
                                                
                                                <input type="text" class="knob" data-readonly="true" value="'.$model->status_id.'" data-width="60" data-height="60" data-fgcolor="#39CCCC" readonly="readonly" style="width: 34px; height: 20px; position: absolute; vertical-align: middle; margin-top: 10px; margin-left: -30px; border: 0px; background: none; font: bold 12px Arial; text-align: center; color: rgb(57, 204, 204); padding: 0px; appearance: none;"></div>
                                        </div>';
                                    return $percent;
                                },
                            ],*/
                            [
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
                            ],
                            [
                                'class' => kartik\grid\ActionColumn::className(),
//                                'class' => 'kartik-sheet-style',
                                'template' => '{view}',
                                'headerOptions' => ['style' => 'background-color: #f5f5f5;'],
                                'buttons' => [

                                    'view' => function ($url, $model){
                                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value' => '/finance/request/view?id=' . $model->request_id,'onclick'=>'location.href=this.value', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View Request")]);
                                        //return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value' => (($model->payroll != 0) ? '/finance/request/viewpayroll?id=' : '/finance/request/view?id=') . $model->request_id,'onclick'=>'location.href=this.value', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View Request")]);
                                    },
                                ],
                            ],
                            [
                                'class' => 'kartik\grid\EditableColumn',
                                'attribute' => 'cancelled',
                                'header' => 'Cancel',
                                'format' => 'raw',
                                'refreshGrid'=>true,
                                'visible' => Yii::$app->user->can('access-finance-verification'),
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->cancelled ? '<i class="glyphicon glyphicon-ok"></i>' : '<i class="glyphicon glyphicon-remove text-red"></i>';
                                },
                                'editableOptions'=> function ($model , $key , $index) {
                                                    return [
                                                        'options' => ['id' => $index . '_11_' . $model->cancelled],
                                                        // 'contentOptions' => ['style' => 'text-align: center;  vertical-align:middle;'],
                                                        'placement'=>'left',
                                                        // 'disabled'=>$model->cancelled,
                                                        'name'=>'district',
                                                        'asPopover' => true,
                                                        'value'=>function ($model, $key, $index, $widget) {
                                                            return $model->cancelled ? '<i class="glyphicon glyphicon-ok"></i>' : '<i class="glyphicon glyphicon-remove text-red"></i>';
                                                        },
                                                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                                                        'data'=>['0'=>'Revert','1'=>'Cancel'],
                                                        'formOptions'=>['action' => ['/finance/request/togglecancel']], // point to the new action
                                                    ];
                                                },
                                'hAlign' => 'right', 
                                'vAlign' => 'middle',
                                'width' => '7%',
                                //'format' => ['decimal', 2],
                                // 'pageSummary' => true
                            ],
                    ],
            
            'pjax' => true, // pjax is set to always true for this demo
            'panel' => [
                    'heading' => Html::encode($this->title),
                    'type' => GridView::TYPE_PRIMARY,
                    'before'=>  Html::button('New Request', ['value' => Url::to(['request/create']), 'title' => 'Create Request', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonCreateRequest']) 
                                .' '. 
                                Html::button('New Payroll', ['value' => Url::to(['request/createpayroll']), 'title' => 'Create Payroll', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonCreateRequest']),
                    'after'=>false,
                ],
            // set your toolbar
            'toolbar' => 
                        [
                            [
                                'content'=> '',
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