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

/* @var $this yii\web\View */
/* @var $searchModel common\models\employeecompensation\PayrollSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// Modal Create Request
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalContainer',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

$this->title = 'Payroll';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payroll-index">

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
                                'attribute'=>'payroll_id',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: center;'],
                                'width'=>'120px',
                                'format'=>'raw',
                                /*'value'=>function ($model, $key, $index, $widget) { 
                                    return '<b>'.$model->request_number.'</b><br/>'.date('Y-m-d', strtotime($model->request_date));
                                },*/
                            ],
                            'obligation_type_id',
            'payroll_date',
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
                    ],
            
            'pjax' => true, // pjax is set to always true for this demo
            'panel' => [
                    'heading' => '',
                    'type' => GridView::TYPE_PRIMARY,
                    'before'=>  Html::button('New Payroll', ['value' => Url::to(['payroll/create']), 'title' => 'Create Payroll', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonCreatePayroll']),
                    'after'=>false,
                ],
            // set your toolbar
            'toolbar' => 
                        [
                            [
                                'content'=>'',
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
