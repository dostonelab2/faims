<?php
use kartik\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use kartik\datecontrol\DateControl;
use kartik\detail\DetailView;
use kartik\editable\Editable; 
use kartik\grid\GridView;

use yii\bootstrap\Modal;

use common\models\procurementplan\Section;
use common\models\procurementplan\Ppmp;
use common\models\procurementplan\PpmpSearch;
use yii\widgets\ActiveForm;

//use common\models\procurement\Division;
/* @var $this yii\web\View */
/* @var $searchModel common\models\procurementplan\PpmpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Budget Allocation';
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
    <?php //echo $selected_year; ?>
    <h3 style="text-align: center"><?= Html::encode('BUDGET ALLOCATION AND MONITORING') ?></h3>


   
   
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
    

        echo GridView::widget([
            'id' => 'ppmp3',
            'dataProvider' => $sectionsDataProvider,
            'columns' => [
                            [
                                'attribute'=>'division', 
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
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    //return $model->name;
                                    return Html::a($model->name, ['budgetallocation/view?id='.$model->budgetallocation->budget_allocation_id], ['data-pjax' => 0, 'target'=>'_blank']);
                                },
                            ],
                            [
                                'attribute'=>'name',
                                'header'=> $year,
                                'headerOptions' => ['style' => 'text-align: center; font-weight: bold;'],
                                'contentOptions' => ['style' => 'padding-right: 100px; text-align: right; font-weight: bold;'],
                                'width'=>'200px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    $budget = $model->budgetallocation ? $model->budgetallocation->getTotal() : '';
                                    $fmt = Yii::$app->formatter;
                                    return $fmt->asDecimal($budget);
                                    //return Html::a($fmt->asDecimal($budget), ['budgetallocation/view?id='.$model->budgetallocation->budget_allocation_id], ['data-pjax' => 0, 'target'=>'_blank']);
                                },
                            ],
                            /*
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
                            ],*/
                    ],
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                //'heading' => $heading,
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
</div>

<script type="text/javascript">
  document.getElementById('dropdown').value = "<?php if(isset($_GET['year'])){
    echo $_GET['year'];
  }?>";

</script>