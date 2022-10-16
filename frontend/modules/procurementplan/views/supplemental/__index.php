<?php

use yii\helpers\Html;
use yii\helpers\Url;

use kartik\editable\Editable;
use kartik\grid\GridView;
use kartik\detail\DetailView;



/* @var $this yii\web\View */
/* @var $searchModel common\models\procurementplan\SupplementalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Supplemental Items';
$this->params['breadcrumbs'][] = ['label' => $model->unit->name, 'url' => ['ppmp/view', 'id' => $model->ppmp_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ppmpitem-index">


      <?php
        $attributes = [
            [
                'group'=>true,
                'label'=>'PPMP DETAILS',
                    //Html::button('Submit PPMP  <i class="glyphicon glyphicon-hand-right"></i>', ['disabled' => $disableSubmitPpmp OR !$isMember, 'value' => Url::to(['ppmp/submit', 'id'=>$model->ppmp_id]), 'title' => 'Submit PPMP', 'class' => 'btn btn-primary', 'style'=>'float: right; margin-right: 6px; display: "";', 'id'=>'buttonSubmitPpmp']).
                    //Html::button('Approved PPMP  <i class="glyphicon glyphicon-thumbs-up"></i>', ['disabled' => !Yii::$app->user->can('approved-ppmp') OR $model->status_id != Ppmp::STATUS_SUBMITTED, 'value' => Url::to(['ppmp/approved', 'id'=>$model->ppmp_id]), 'title' => 'Approved PPMP', 'class' => 'btn btn-primary', 'style'=>'float: right; margin-right: 6px; display: "";', 'id'=>'buttonSubmitPpmp'])
                    
                'rowOptions'=>['class'=>'info'],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>$model->unit_id ? 'unit_id' : 'project_id',
                        'value'=>$model->unit_id ? $model->unit->name : Project::findOne($model->project_id)->code,
                        'valueColOptions'=>['style'=>'width:100px'],
                    ],
                    [
                        'attribute'=>'year',
                        'valueColOptions'=>['style'=>'width:30%'],
                        'label'=>'Year',
                    ],
                ],
            ],
            [
                'columns' => [
                    
                    [
                        'attribute'=>'division_id', 
                        'label'=>'Division',
                        'displayOnly'=>true,
                        'value'=>$model->project_id ? '' : $model->division->name,
                        'valueColOptions'=>['style'=>'width:100px'],
                    ],  
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>$model->project_id ? 'unit_id' : 'project_id',
                        'valueColOptions'=>['style'=>'width:100px'],
                    ],
                ],
            ],
            [
                'group'=>true,
                'label'=>'BUDGET ALLOCATION',
                'rowOptions'=>['class'=>'info'],
                //'groupOptions'=>['class'=>'text-center']
            ],
            [
                'attribute'=>'charged_to',
                'label'=>'Approved Budget (Php)',
                'value'=>$model->project_id ? $model->project->budgetallocation->getTotal() : $model->unit->budgetallocation->getTotal(),
                'format'=>['decimal', 2],
                'inputContainer' => ['class'=>'col-sm-6'],
            ],
            [
                'attribute'=>'year',
                'label'=>'Running Total (Php)',
                'value'=>$model->getRunningTotal(),
                'format'=>['decimal', 2],
                'inputContainer' => ['class'=>'col-sm-6'],
            ],
            [
                'label'=>'Remaining Budget (Php)',
                'value'=>($model->project_id ? $model->project->budgetallocation->getTotal() : $model->unit->budgetallocation->getTotal()) - $model->getRunningTotal(),
                //'value'=>$model->unit->budgetallocation ? ($model->unit->budgetallocation->getTotal() - $model->getRunningTotal()) : - $model->getRunningTotal(),
                'format'=>['decimal', 2],
                'inputContainer' => ['class'=>'col-sm-6'],
                // hide this in edit mode by adding `kv-edit-hidden` CSS class
                'rowOptions'=>['class'=>'warning kv-edit-hidden', 'style'=>'border-top: 5px double #dedede; texl-align: right;'],
            ],
        ];
        
        echo DetailView::widget([
            'model' => $model,
            'mode'=>DetailView::MODE_VIEW,
            /*'deleteOptions'=>[ // your ajax delete parameters
                'params' => ['id' => 1000, 'kvdelete'=>true],
            ],*/
            'container' => ['id'=>'kv-demo'],
            //'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
            
            //'buttons1' => '', //hides buttons on detail view
            'attributes' => $attributes,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            'panel' => [
                //'type' => 'Primary', 
                'heading'=>'<i class="glyphicon glyphicon-book"></i> PPMP - '.($model->project_id ? Project::findOne($model->project_id)->code : $model->unit->name).' - '.$model->year,
                'type'=>DetailView::TYPE_PRIMARY,
                //'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
            ],

            
        ]);
    ?>

<?php

         if($dataProvider->totalCount > 0){
            $visible = true;
         }else{
            $visible = false;
         }

$gridColumns = [

                [
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '20px',
                    'header' => '',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    //'mergeHeader' => true,
                ],
                [
                    'attribute'=>'month',
                    'header'=>'Month',
                    'visible' => $visible,
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            if($model->month == 1){
                                return 'January';
                            }elseif ($model->month == 2) {
                                return 'February';
                            }elseif ($model->month == 3) {
                                return 'March';
                            }elseif ($model->month == 4) {
                                return 'April';
                            }elseif ($model->month == 5) {
                                return 'May';
                            }elseif ($model->month == 6) {
                                return 'June';
                            }elseif ($model->month == 7) {
                                return 'July';
                            }elseif ($model->month == 8) {
                                return 'August';
                            }elseif ($model->month == 9) {
                                return 'September';
                            }elseif ($model->month == 10) {
                                return 'October';
                            }elseif ($model->month == 11) {
                                return 'November';
                            }elseif ($model->month == 12) {
                                return 'December';
                            }
                        },
                    'headerOptions' => ['style' => 'text-align: left; background-color: #7e9fda;'],
                    'contentOptions' => ['style' => 'text-align: left; background-color: #7e9fda; font-weight: bold;'],
                
                    'group'=>true,  // enable grouping,
                    'groupedRow'=>true,                    // move grouped column to a single grouped row
                    'groupOddCssClass'=>'',  // configure odd group cell css class
                    'groupEvenCssClass'=>'', // configure even group cell css class
                    
                ],


                [
                    'attribute'=>'description', 
                    'header'=>'General Description',
                    'width'=>'300px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: left'],
                    'mergeHeader' => true,
                ],
                         [
                    'attribute'=>'unit', 
                    'header'=>'Unit of Measure',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->unitofmeasure->name ? $model->unitofmeasure->name : '';
                        },
                    'width'=>'100px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: center'], 
                    'mergeHeader' => true,
                ],
                [
                    'attribute'=>'cost',
                    'header'=>'Unit Cost',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal($model->cost);
                        },
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'quantity', 
                    'header'=>'Quantity',
                    'width'=>'100px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: right;'],
                    'mergeHeader' => true,
                ],
                            [
                    'attribute'=>'estimated_budget',
                    'header'=>'Estimated Budget',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'width'=>'75px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal($model->getTotalamount());
                        },
                    //'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                  ['class' => 'kartik\grid\ActionColumn',
                    'template' => '{approve}',    
                    'buttons' => [
                        'approve' => function ($url,$model)
                        {
                                #return Html::a('Approve <span class="glyphicon glyphicon-thumbs-up"></span>',['view','id' => $model->ppmp_id],['class' => 'btn btn-Primary btn-sm','data-toggle' => 'tooltip', 'title' => 'approve']);

                                return Html::a('Approve <span class="glyphicon glyphicon-thumbs-up"></span>', ['create'], [
                                                                        'class' => 'btn btn-success',
                                                                        'style' => 'margin-right: 6px; display: "";',
                                                                        'data' => [
                                                                            'confirm' => 'Are you sure you want to Approve this item?',
                                                                            'method' => 'post',],]);
                                #return Html::a('<button type="button" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-eye-open"></span></button>',$url);  
                                #return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['href' => Url::to($url),'class' => 'btn btn-success btn-sm']); 
                        },
              
                    ], 
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                ],
            
            ];


echo GridView::widget([
        'dataProvider'=> $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'pjax'=>true,
        //'responsive'=>false,
        //'hover'=>true,
        'panel' => [
                'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Supplemental Items</h3>',
                'type'=>'primary',
                //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Create Country', ['create'], ['class' => 'btn btn-success']),
                //'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
                'footer'=>false
            ], 
    /*[
                    'heading'=>'<h3 class="panel-title">Supplemental Items</h3>',
                    'type'=>'primary',
                    'before'=> Html::a('Add Item', ['additem','id' => $model->ppmp_id, 'year' => $model->year], [
                                                                    'class' => 'btn btn-primary',
                                                                    'style' => 'margin-right: 6px; display: "";',
                                                                    'data' => [
                                                                        'confirm' => 'Are you sure you want to add supplemental item',
                                                                        'method' => 'post',],])
                ],*/
        'toolbar' => 
                                [
                                    [
                                        'content'=> Html::a('Add Item', ['additem','id' => $model->ppmp_id, 'year' => $model->year], [
                                                                    'class' => 'btn btn-primary',
                                                                    'style' => 'margin-right: 6px; display: "";',
                                                                    'data' => [
                                                                        //'confirm' => 'Are you sure you want to add supplemental item',
                                                                        'method' => 'post',],])
                                    ],
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

    

</div>
