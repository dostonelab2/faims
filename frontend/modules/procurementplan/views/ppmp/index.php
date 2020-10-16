<?php
use kartik\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use kartik\datecontrol\DateControl;
use kartik\detail\DetailView;
use kartik\editable\Editable; 
use kartik\grid\GridView;
use yii\widgets\ActiveForm;

use yii\bootstrap\Modal;

use common\models\procurementplan\Section;
use common\models\procurementplan\Ppmp;
use common\models\procurementplan\PpmpSearch;

//use common\models\procurement\Division;
/* @var $this yii\web\View */
/* @var $searchModel common\models\procurementplan\PpmpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'PPMP';
$this->params['breadcrumbs'][] = $this->title;

// Modal PPMP
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalPpmp',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalPpmpItem',
    'size' => 'modal-lg',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();
?>    
<div class="ppmp-index">

    <div class="panel panel-primary">
      <div class="panel-heading" style="text-align: center">
        <h3><?= Html::encode('PROJECT PROCUREMENT MANAGEMENT PLAN (PPMP - '.$selected_year.')') ?></h3>
      </div>
      <div class="panel-body">

    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]);

    echo $form->field($searchSectionModel, 'selectyear')->dropDownList(
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

        echo GridView::widget([
            'id' => 'ppmp',
            'dataProvider' => $ppmpDataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                            //'section_id',
                            //'division_id',
                            [
                                'attribute'=>'division',
                                'visible' => $ppmpDataProvider->totalCount > 0 ? true : false,
                                'width'=>'250px',
                                'contentOptions' => ['style' => 'max-width:25px;white-space:normal;word-wrap:break-word;font-weight:bold'],
                                'value'=>function ($model, $key, $index, $widget) use ($selected_year) { 
                                    return $model->division->name;
                                },
                                'group'=>true,  // enable grouping,
                                'groupedRow'=>true,                    // move grouped column to a single grouped row
                                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                            ],
                            [
                                'attribute'=>'name',
                                'contentOptions' => ['style' => 'padding-left: 25px'],
                                'width'=>'250px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->name;
                                },
                            ],
                            [
                                //'attribute'=>'name',
                                'header'=>'Budget Allocation',
                                'contentOptions' => ['style' => 'padding-left: 25px; text-align: right; font-weight: bold;'],
                                'width'=>'200px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    $budget = $model->budgetallocation ? $model->budgetallocation->getTotal() : '';
                                    $fmt = Yii::$app->formatter;
                                    //return Html::a($fmt->asDecimal($budget), ['budgetallocation/view?id='.$this->budget_allocation_id]);
                                    return $fmt->asDecimal($budget);
                                },
                            ],
                            [
                                'attribute'=>'status',
                                'header'=>'PPMPs',
                                'width'=>'250px',
                                'headerOptions' => ['style' => 'text-align: center'],
                                'contentOptions' => ['style' => 'text-align: center'],
                                'format' => 'raw',
                                'value'=>function ($model) { 
                                    return $model->getPpmps();
                                },
                            ],
                            /*[
                                'attribute'=>'year',
                                'header'=>'Year',
                                'format'=>'raw',
                                'width'=>'250px',

                                'value'=>function($model,$key,$index,$widget) use ($selected_year) {
                                    $ppmp_id = $model->getPpmpYear($model->section_id, 2019);
                                    return Html::a('View', '',
                                        ['onclick' => "window.open ('".Url::toRoute(['view', 'id' => $ppmp_id])."'); return false", 
                                         'class' => 'btn btn-small btn-success']);
                                },
                            ],
                            [
                                'attribute'=>'ppmp',
                                'header'=>'Status',
                                'width'=>'250px',
                                'value'=>function($model,$key,$index,$widget) use ($selected_year) {
                                    //return $model->getPpmp($model->section_id,$selected_year);
                                },
                            ],
                            [
                                'class' => '\kartik\grid\ActionColumn',
                                'template' => '{view}{update}{create}',
                                'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>'],
                                'buttons' => 
                                    [
                                        
                                    ]
                            ]*/
                    ],
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            //'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            /*'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                //'heading' => $heading,
                'content'=>[
                    Html::button('New PPMP', ['value' => Url::to(['ppmp/create']), 'title' => 'PPMP', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddPpmp']) . ' ' .
                                    Html::dropDownList('name', $selected_year, ['2019' => '2019', '2020' => '2020'], 
                                                       [
                                                            'data-pjax' => true, 
                                                            'class' => 'btn btn-default',
                                                            'id' => 'year',
                                                       ])
                ]
            ],*/
            'panel' => [
                    'heading' => '<b>Functional Units</b>',
                    'type' => GridView::TYPE_PRIMARY,
                    //'before'=>Html::button('New PPMP', ['value' => Url::to(['ppmp/create']), 'title' => 'PPMP', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddPpmp']),
                    'after'=>false,
                    //'before' => $dropDownList,
                ],
            // set your toolbar
            'toolbar' => 
                        [
                            [
                                'content'=>
                                    Html::button('PENDING', ['title' => 'Approved', 'class' => 'btn btn-warning', 'style'=>'width: 90px; margin-right: 6px;']) .    
                                    Html::button('SUBMITTED', ['title' => 'Approved', 'class' => 'btn btn btn-info', 'style'=>'width: 90px; margin-right: 6px;']) .
                                    Html::button('APPROVED', ['title' => 'Approved', 'class' => 'btn btn-success', 'style'=>'width: 90px; margin-right: 6px;'])
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
        
        
        
        
        
        
        
        <?php
        echo GridView::widget([
            'id' => 'projects',
            'dataProvider' => $projectDataProvider,
            'columns' => [
                            //'project_id',
                            [
                                'attribute'=>'code',
                                'contentOptions' => ['style' => 'padding-left: 25px'],
                                'width'=>'250px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->code;
                                },
                            ],
                            [
                                'attribute'=>'name',
                                'contentOptions' => ['style' => 'padding-left: 25px'],
                                'width'=>'250px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return substr($model->name, 0, 100) . ' ...';
                                },
                            ],
                            [
                                'attribute'=>'name',
                                'header'=>'Budget Allocation',
                                'contentOptions' => ['style' => 'text-align: right; font-weight: bold;'],
                                'width'=>'250px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->budgetallocation->getTotal();
                                    //return '0.00';
                                },
                            ],
                            [
                                'attribute'=>'status',
                                'header'=>'PPMPs',
                                'width'=>'250px',
                                'headerOptions' => ['style' => 'text-align: center'],
                                'contentOptions' => ['style' => 'text-align: center'],
                                'format' => 'raw',
                                'value'=>function ($model) { 
                                    return $model->getPpmps();
                                },
                            ],
                    ],
            //'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            //'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            'panel' => [
                    'heading' => '<b>Projects</b>',
                    'type' => GridView::TYPE_PRIMARY,
                    //'before'=>Html::button('New PPMP', ['value' => Url::to(['ppmp/create']), 'title' => 'PPMP', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddPpmp']),
                    'after'=>false,
                ],
            // set your toolbar
            'toolbar' => 
                        [
                            [
                                'content'=>
                                    Html::button('PENDING', ['title' => 'Approved', 'class' => 'btn btn-warning', 'style'=>'width: 90px; margin-right: 6px;']) .    
                                    Html::button('SUBMITTED', ['title' => 'Approved', 'class' => 'btn btn btn-info', 'style'=>'width: 90px; margin-right: 6px;']) .
                                    Html::button('APPROVED', ['title' => 'Approved', 'class' => 'btn btn-success', 'style'=>'width: 90px; margin-right: 6px;'])
                            ],
                            //'{export}',
                            //'{toggleData}'
                        ],
            
            'toggleDataOptions' => ['minCount' => 10],
            'itemLabelSingle' => 'item',
            'itemLabelPlural' => 'items'
        ]);
        ?>
        </div>
    </div>
</div>

<script type="text/javascript">
  document.getElementById('dropdown').value = "<?php if(isset($_GET['year'])){
    echo $_GET['year'];
  }?>";

</script>