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
use common\models\finance\Request;
use common\models\system\Profile;
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

///echo '<span class="badge btn-success">'.$numberOfRequests.'</span>';
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
            'dataProvider' => $dataProvider,
            'columns' => [
                            /*[
                                'attribute'=>'request_id',
                                'contentOptions' => ['style' => 'padding-left: 25px; font-weigth: bold;'],
                                'width'=>'80px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->request->request_number;
                                },
                            ],*/
                            [
                                'attribute'=>'osdv_id',
                                'header'=>'OS Number',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'width'=>'150px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return isset($model->os->os_id) ? $model->os->os_number.'<br/>'.$model->os->os_date : '';
                                },
                            ],
                            [
                                'attribute'=>'osdv_id',
                                'header'=>'DV Number',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'width'=>'150px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return isset($model->dv->dv_id) ? $model->dv->dv_number : '';
                                },
                            ],
                            [
                                'attribute'=>'request_date',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'width'=>'150px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return date('Y-m-d', strtotime($model->request->request_date));
                                },
                            ],
                            [
                                'attribute'=>'payee_id',
                                'contentOptions' => ['style' => 'padding-left: 25px; font-weigth: bold;'],
                                'width'=>'550px',
                                'contentOptions' => [
                                    'style'=>'max-width:300px; overflow: auto; white-space: normal; word-wrap: break-word;'
                                ],
                                'format' => 'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return '<b>' . Creditor::findOne($model->request->payee_id)->name. '</b><br>' .$model->request->particulars;
                                },
                            ],
                            [
                                'attribute'=>'amount',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'width'=>'150px',
                                'value'=>function ($model, $key, $index, $widget) {
                                    $fmt = Yii::$app->formatter;
                                    return $fmt->asDecimal($model->request->amount);
                                },
                            ],
                            [
                                'attribute'=>'created_by',
                                'contentOptions' => ['style' => 'padding-left: 25px;'],
                                'width'=>'250px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return Profile::find($model->created_by)->one()->fullname;
                                },
                            ],
                            [
                                'class' => kartik\grid\ActionColumn::className(),
                                'template' => '{view}',
                                'buttons' => [

                                    'view' => function ($url, $model){
                                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value' => '/finance/osdv/view?id=' . $model->osdv_id,'onclick'=>'location.href=this.value', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View Request")]);
                                    },
                                ],
                            ],
                            [
                                'class' => 'kartik\grid\CheckboxColumn',
                                'headerOptions' => ['class' => 'kartik-sheet-style'],
                                'pageSummary' => '<small>(amounts in $)</small>',
                                'pageSummaryOptions' => ['colspan' => 3, 'data-colspan-dir' => 'rtl']
                            ],
                    ],
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            'rowOptions' => function($model){
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
                 
            },
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
<script>
/*$( document ).ready(function() {
    setTimeout(function(){
        window.location = 'verifyindex';
    }, 5000);
});)*/

$( document ).ready(function() {
    setTimeout(function(){
       window.location.reload(1);
    }, 20000);
});
</script>