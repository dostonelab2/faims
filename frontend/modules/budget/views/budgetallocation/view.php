<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

//use kartik\detail\DetailView;
//use kartik\editable\Editable;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\bootstrap\Modal;

use common\models\budget\Budgetallocationitemdetails;
use common\models\budget\Budgetallocationitem;
/* @var $this yii\web\View */
/* @var $model common\models\budget\Budgetallocation */

$this->title = $model->section->name;
$this->params['breadcrumbs'][] = ['label' => 'Budgetallocations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalBudget',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

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

?>
<div class="budgetallocation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'budget_allocation_id',
            //'section_id',
            [
                        'attribute'=>'section_id',
                        'value'=>$model->section->name,
                        'valueColOptions'=>['style'=>'width:30%'],
                    ],
            'year',
            'amount',
        ],
    ]) ?>

</div>


<?php
            
            $gridColumns = [
                /*[
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '20px',
                    'header' => '',
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    //'mergeHeader' => true,
                ],*/
                [
                    'attribute' => 'budget_allocation_item_id',
                    'header'=>'ID',
                    'width'=>'20px',
                    /*'value'=>function ($model, $key, $index, $widget){ 
                                $fmt = Yii::$app->formatter;
                                return $fmt->asDecimal(0);
                            },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: center'],*/
                ],
                [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'width' => '50px',
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    // uncomment below and comment detail if you need to render via ajax
                    // 'detailUrl'=>Url::to(['/site/book-details']),
                    'detail' => function ($model, $key, $index, $column) use ($year){
                        
                        if($model->category_id == 87){
                            $query = Budgetallocationitemdetails::find()->where(['budget_allocation_item_id' => $model->budget_allocation_item_id, 'active' => 1]);

                            $dataProvider = new ActiveDataProvider([
                                'query' => $query,
                                'pagination' => false,
                            ]);
                            
                            return Yii::$app->controller->renderPartial('_programs_gia', ['dataProvider' => $dataProvider, 'budgetAllocationItemId' =>$model->budget_allocation_item_id, 'year'=>$year]);
                        }elseif($model->category_id == 88){
                            $query = Budgetallocationitemdetails::find()->where(['budget_allocation_item_id' => $model->budget_allocation_item_id, 'active' => 1]);

                            $dataProvider = new ActiveDataProvider([
                                'query' => $query,
                                'pagination' => false,
                            ]);
                            
                            return Yii::$app->controller->renderPartial('_programs_setup', ['dataProvider' => $dataProvider, 'budgetAllocationItemId' =>$model->budget_allocation_item_id, 'year'=>$year]);
                        }   
                    },
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'expandOneOnly' => false,
                    //'expandIcon' => '+',
                    //'collapseIcon' => '-',
                    //'enableRowClick' => true,
                ],
                // ############################################################################## //
                [
                    'attribute'=>'expenditure_class_id',
                    //'header'=>'Category',
                    //'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->expenditureClass->name;
                        },
                    //'headerOptions' => ['style' => 'text-decoration: underline;'],
                    'contentOptions' => ['style' => 'font-variant:small-caps; text-align: left; font-weight: bold; text-decoration: underline; font-size: large;', ],
                
                    'group'=>true,  // enable grouping,
                    'groupedRow'=>true,                    // move grouped column to a single grouped row
                    //'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                    //'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                ],
                [
                    'attribute'=>'expenditure_subclass_id',
                    //'header'=>'Category',
                    //'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->expenditureSubclass->name;
                        },
                    //'headerOptions' => ['style' => 'text-align: left;'],
                    //'contentOptions' => ['style' => 'text-align: left; font-weight:bold; padding-left: 35px;'],
                
                    'group'=>true,  // enable grouping,
                    'groupedRow'=>true,                    // move grouped column to a single grouped row
                    //'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                    //'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                    'groupFooter' => function ($model, $key, $index, $widget) { // Closure method
                        return [
                            'mergeColumns' => [
                                [0,1],
                            ], // columns to merge in summary
                            'content' => [             // content to show in each summary cell
                                3 => 'TOTAL : '.$model->expenditureSubclass->name,
                                4 => GridView::F_SUM,
                            ],
                            'contentFormats' => [      // content reformatting for each summary cell
                                4 => ['format' => 'number', 'decimals' => 2],
                                //3 => ['format' => 'number', 'decimals' => 2],
                            ],
                            'contentOptions' => [      // content html attributes for each summary cell
                                3 => ['style' => 'font-variant:small-caps'],
                                4 => ['style' => 'text-align:right'],
                            ],
                            // html attributes for group summary row
                            'options' => ['class' => 'info table-info', 'style' => 'font-weight:bold; text-align: right;']
                        ];
                    }
                ],
                [
                    'attribute'=>'name', 
                    'header'=>'Object of Expenditures',
                    'width'=>'680px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->name;
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: left'],
                    //'mergeHeader' => true,
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'amount',
                    'header'=>'Fund Allocation',
                    'width'=>'250px',
                    'refreshGrid'=>true,
                    'readonly' => true,
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal($model->itemdetails ? $model->getTotal() : $model->amount);
                        },
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->budget_allocation_item_id],
                            'placement'=>'left',
                            //'disabled'=>$model->itemdetails ? true : !Yii::$app->user->can('access-budget-management'),
                            //'disabled'=>true,
                            'name'=>'amount',
                            'asPopover' => true,
                            'value' => $model->amount,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/budget/budgetallocationitem/updateamount']], // point to the new action
                        ];
                    },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                /*[
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{update}',
                    'dropdown' => false,
                    'dropdownOptions' => ['class' => 'pull-right'],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action === 'delete') {
                            $url ='/lab/sample/delete?id='.$model->sample_id;
                            return $url;
                        } 
                        if ($action === 'cancel') {
                            $url ='/lab/sample/cancel?id='.$model->sample_id;
                            return $url;
                        }
                    },
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'buttons' => [
                        'update' => function ($url, $model) {
                            if($model->active == 1){
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', ['class'=>'btn btn-primary','title'=>'Add Adjustment','onclick' => 'addAdjustment('.$model->budget_allocation_item_id.')']);
                            } else {
                                return null;
                            }
                        },
                    ],
                ],*/
                [
                    'attribute' => 'amount',
                    'header'=>'Adjustments',
                    'width'=>'100px',
                    'format'=>'raw',
                    'value'=>function ($model, $key, $index, $widget){ 
                                return Html::button('<i class="glyphicon glyphicon-share"></i>', ['value' => Url::to(['allocationadjustment/create', 'id'=>$model->budget_allocation_id, 'itemid'=>$model->budget_allocation_item_id, 'itemdetailid'=>0]), 'title' => 'Create Adjustments', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonAdjustment']) . 
                                
                                Html::button('<i class="glyphicon glyphicon-list"></i>', ['value' => Url::to(['allocationadjustment/view', 'id'=>$model->budget_allocation_item_id]), 'title' => 'View Adjustments', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonViewAdjustment']);
                            },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: center'],
                ],
                [
                    'attribute' => 'amount',
                    'header'=>'% from NEP',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) use ($year){ 
                                $fmt = Yii::$app->formatter;
                                if($model->amount == 0){
                                    return 0;
                                }else{
                                    $model->year = $year;
                                    //return $fmt->asPercent( isset($model->expenditure->amount) ? ($model->amount / $model->expenditure->amount) : '');
                                   return 0;
                                }
                                
                            },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: center'],
                ],
                [
                    'attribute' => 'amount',
                    'header'=>'Actual Expenditure',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget){ 
                                $fmt = Yii::$app->formatter;
                                return $fmt->asDecimal(0);
                            },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: center'],
                ],
                [
                    'attribute' => 'amount',
                    'header'=>'Fund Available',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget){ 
                                $fmt = Yii::$app->formatter;
                                return $fmt->asDecimal(0);
                            },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: center'],
                ],
            ];
            
            echo GridView::widget([
                'id' => 'ppmp-items',
                'dataProvider' => $budgetAllocationItemsDataProvider,
                //'filterModel' => $searchModel,
                'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => true, // pjax is set to always true for this demo
                // set left panel buttons
                'panel' => [
                    'heading'=>'<h3 class="panel-title">BUDGET ALLOCATION</h3>',
                    'type'=>'primary',
                ],
                // set right toolbar buttons
                'toolbar' => 
                                [
                                    [
                                        'content'=>                                            
                                            Html::button('Add Item  <i class="glyphicon glyphicon-list"></i>', ['value' => Url::to(['budgetallocationitem/additems', 'id'=>$model->budget_allocation_id, 'year'=>$model->year]), 'title' => 'Add Budget Allocation Items', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonAddBudgetallocationItem'])
                                    ],
                                ],
                // set export properties
                'export' => [
                    'fontAwesome' => true
                ],
                'persistResize' => false,
                'toggleDataOptions' => ['minCount' => 10],
            ]);

            //echo $getyear;
    
        ?>
        
<script>
$( document ).ready(function() {
    setTimeout(
      function() {
        //$("tr.info td:first-child").hide();
      }, 0);
}); 
</script>