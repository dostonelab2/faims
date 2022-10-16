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
    <?php //echo $selected_year; ?>
    <h3 style="text-align: center"><?= Html::encode('PROJECT PROCUREMENT MANAGEMENT PLAN (PPMP)') ?></h3>

   
   
        <?php
        echo GridView::widget([
            'id' => 'ppmp3',
            'dataProvider' => $ppmpDataProvider,
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
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->name;
                                },
                            ],
                            [
                                'attribute'=>'name',
                                'header'=>'Budget Allocation',
                                'contentOptions' => ['style' => 'padding-left: 25px; text-align: right; font-weight: bold;'],
                                'width'=>'200px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->budgetallocation;
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
                                'content'=>
                                    Html::button('PENDING', ['title' => 'Approved', 'class' => 'btn btn-warning', 'style'=>'width: 90px; margin-right: 6px;']) .    
                                    Html::button('SUBMITTED', ['title' => 'Approved', 'class' => 'btn btn-primary', 'style'=>'width: 90px; margin-right: 6px;']) .
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
</div>