<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use kartik\datecontrol\DateControl;
use kartik\detail\DetailView;
use kartik\editable\Editable; 
use kartik\grid\GridView;
use kartik\helpers\Html;

use common\models\budget\Budgetallocationitem;
use common\models\procurementplan\Ppmp;

use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurementplan\ExpenditureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// Modal Expenditure
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalExpenditure',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

$this->title = 'Expenditures';
$this->params['breadcrumbs'][] = ['label' => ucfirst(Yii::$app->controller->module->id), 'url' => ['/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expenditure-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
            $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
            ]);
        
            echo $form->field($searchModel, 'selectyear')->dropDownList(
                ArrayHelper::map(Ppmp::find()->all(),'year', 'year'),
            [
                'class' => 'form-control',
                'prompt' => 'Select Year...',
                'name' => 'year',
                //'onchange' => 'selectMonth(this.value)',
                'id' => 'dropdown',
                'onchange' => 'this.form.submit()',
                'style'=>'width:250px; font-weight:bold;'
            ]
            )->label(false);
            
        
        
            ActiveForm::end();
    
            if(isset($_GET['year'])){
                $year = $_GET['year'];
            }else{
                $year = date('Y');
            }
            
            $gridColumns = [
                [
                    'class' => 'kartik\grid\SerialColumn',
                    //'contentOptions' => ['class' => 'kartik-sheet-style'],
                    //'width' => '20px',
                    'header' => '',
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    //'mergeHeader' => true,
                ],
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
                    'header'=>'Appropriation',
                    'refreshGrid'=>true,
                    //'readonly' => Yii::$app->user->can('access-budget-management'),
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal($model->amount);
                            //return $fmt->asCurrency($model->getBudgetAllocationTotal(), 'Php');
                        },
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->expenditure_id],
                            'placement'=>'left',
                            'disabled'=>!Yii::$app->user->can('access-budget-management'),
                            'name'=>'amount',
                            'asPopover' => true,
                            'value' => $model->amount,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/budget/expenditure/updateamount']], // point to the new action
                        ];
                    },
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
                [
                    'attribute'=>'amount',
                    'header'=>'as per Budget Allocation',
                    'width'=>'100px',
                    'format'=>'raw',
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal($model->getBudgetAllocationTotal());
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'amount',
                    'header'=>'% Budget Allocation',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asPercent($model->getBudgetAllocationPercent());
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'amount',
                    'header'=>'Actual Expenditures',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal(0.00);
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
                [
                    'attribute'=>'amount',
                    'header'=>'% Actual Expenditures',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal(0.00);
                        },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'contentOptions' => ['style' => 'text-align: right'],
                ],
            ];
            
            echo GridView::widget([
                'id' => 'expenditure-objects',
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => true, // pjax is set to always true for this demo
                // set left panel buttons
                'panel' => [
                    'heading'=>'<h3 class="panel-title">BUDGET ESTIMATE per NATIONAL EXPENDITURE PROGRAM</h3>',
                    'type' => GridView::TYPE_PRIMARY,
                    'before'=>Html::button('Add Expenditure', ['value' => Url::to(['expenditure/addexpenditures','year'=>$year]), 'title' => 'Expenditure', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddExpenditure']),
                    'after'=>false,
                ],
                // set right toolbar buttons
                'toolbar' => 
                                [
                                    [
                                        'content'=>'',
                                    ],
                                ],
                // set export properties
                'export' => [
                    'fontAwesome' => true
                ],
                'persistResize' => false,
                'toggleDataOptions' => ['minCount' => 10],
                //'exportConfig' => $exportConfig,
                //'itemLabelSingle' => 'item',
                //'itemLabelPlural' => 'items'
                
                //'showPageSummary' => true,
                //'pageSummaryRowOptions' => ['class' => 'kv-page-summary warning'],
            ]);
    
        ?>
</div>

<script type="text/javascript">
  document.getElementById('dropdown').value = "<?php if(isset($_GET['year'])){
    echo $_GET['year'];
  }?>";

</script>
