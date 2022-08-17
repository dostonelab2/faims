<script type="text/javascript">
    $(function() {
        $(".knob").knob();
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Knob/1.2.13/jquery.knob.min.js"></script>

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
use common\models\finance\Requestdistrict;
use common\models\finance\Requeststatus;
use common\models\procurement\Division;
use common\models\system\Profile;
use common\models\system\Usersection;
use common\models\sec\Blockchain;
/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'LDDAP-ADA';
$this->params['breadcrumbs'][] = $this->title;

// Modal Create LDDAP-ADA
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalLddapada',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

?>

<div class="lddapada-index">

    <h1><?= Html::encode($this->title) ?></h1>
<?php Pjax::begin(); ?>
      <?php
        echo GridView::widget([
            'id' => 'lddapada',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
//            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
//            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'columns' => [
                            [
                                'attribute'=>'batch_number',
                                'header'=> 'Batch Number | Date | Total Amount',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'width'=>'15%',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    $fmt = Yii::$app->formatter;
                                    return '<b>'.Html::a($model->lddapada->batch_number, ['lddapada/view', 'id'=>$model->lddapada_id], ['style' => 'font-size: medium;', 'target' => '_blank', 'data-pjax'=>0]).'</b><br/>'.date('Y-m-d',strtotime($model->lddapada->batch_date)).'<br/><b style="font-size: large">'.$fmt->asDecimal($model->lddapada->getTotal()).'</b>';
                                },
                                'group'=>true,  // enable grouping,
//                                'groupedRow'=>true,                    // move grouped column to a single grouped row
//                                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
//                                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                                'groupFooter' => function ($model, $key, $index, $widget) { // Closure method
                                return [
//                                    'mergeColumns' => [
//                                        [0,1,],
//                                    ], // columns to merge in summary
                                    'content' => [             // content to show in each summary cell
//                                        2 => 'TOTAL :',
//                                        3 => GridView::F_SUM,
                                    ],
//                                    'contentFormats' => [      // content reformatting for each summary cell
//                                        4 => ['format' => 'number', 'decimals' => 2],
//                                        3 => ['format' => 'number', 'decimals' => 2],
//                                    ],
                                    'contentOptions' => [      // content html attributes for each summary cell
                                        3 => ['style' => 'font-variant:small-caps'],
//                                        4 => ['style' => 'text-align:right'],
                                    ],
                                    // html attributes for group summary row
                                    'options' => ['class' => 'info table-info', 'style' => 'font-weight:bold; text-align: right;']
                                ];
                            }
                            ],
                            [
                                'attribute'=>'type_id',
                                'header'=>'Fund Source',
                                'headerOptions' => ['style' => 'text-align: center; vertical-align: middle; font-size: medium;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'width'=>'10%',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->lddapada->type_id ? $model->lddapada->fundsource->name : '-';
                                },
                            ],

                            [
                                'attribute'=>'name',
                                'header'=>'Creditors',
                                'headerOptions' => ['style' => 'text-align: center; vertical-align: middle; font-size: medium;'],
                                'contentOptions' => ['style' => 'padding-left: 25px;'],
                                'width'=>'250px',
                                'format'=>'raw',

                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Creditor::find()->asArray()->all(), 'name', 
                                                                function($model) {
                                                                    return $model['name'];
//                                                                        .' | '.$model['address'];
                                                                }
                                                            ), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],  
                                'filterInputOptions' => ['placeholder' => 'Select Payee']
                            ],
                            [
                                'attribute'=>'gross_amount',
                                'header'=>'Gross Amount',
                                'headerOptions' => ['style' => 'text-align: center; vertical-align: middle; font-size: medium;'],
                                'contentOptions' => ['style' => 'text-align: right; padding-right: 10px; font-weight: bold;'],
                                'width'=>'10%',
                                'format'=>['decimal',2],
//                                'valu
                            ],
                                                        [
                                'attribute'=>'osdv_id',
                                'header'=>'Request # | OS Number | DV Number',
                                'headerOptions' => ['style' => 'text-align: center; vertical-align: middle; font-size: medium;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'width'=>'30%',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return 
                                        
                                        Html::a($model->osdv->request->request_number, ['/finance/request/view', 'id'=>$model->osdv->request->request_id], ['target' => '_blank', 'data-pjax'=>0, 'class'=>'btn btn-primary'])
                                            
                                        .'  ' //space between buttons
                                            
//                                        .($model->osdv->os ? $model->osdv->os->os_number : "-")
                                        .($model->osdv->os ? Html::a($model->osdv->os->os_number, Url::to(['/finance/request/printos', 'id'=>$model->osdv->request->request_id]), ['target' => '_blank', 'data-pjax'=>0, 'class'=>'btn btn-primary']) : "")
                                        
                                        
                                        .'  ' //space between buttons
                                        
                                        
                                        .($model->osdv->dv ?  
                                        ($model->request_payroll_id ? 
                        
                        Html::a($model->osdv->dv->dv_number, Url::to(['/finance/request/printdvpayroll', 'id'=>$model->request_payroll_id]), ['target' => '_blank', 'data-pjax'=>0, 'class'=>'btn btn-primary']) 
                            : 
                        Html::a($model->osdv->dv->dv_number, Url::to(['/finance/request/printdv', 'id'=>$model->osdv->request->request_id]), ['target' => '_blank', 'data-pjax'=>0, 'class'=>'btn btn-primary']) ) : "-")
                                        ;
                                },
                            ],
                    ],
            
            'pjax' => true, // pjax is set to always true for this demo
            'panel' => [
                    'heading' => '',
                    'type' => GridView::TYPE_PRIMARY,
//                    'before'=>  Html::button('New Request', ['value' => Url::to(['request/create']), 'title' => 'Create Request', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonCreateRequest'])
                    'before'=>Html::button('New LDDAP-ADA ( <b>'.$count.'</b> )', ['value' => Url::to(['lddapada/create']), 'title' => $new_items, 'data-toggle' => 'tooltip',  'data-placement' => 'right','class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonCreateLddapada']),
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