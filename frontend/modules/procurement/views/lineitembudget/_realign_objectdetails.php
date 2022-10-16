<?php
use kartik\helpers\Html;
use yii\helpers\Url;

use kartik\grid\GridView;
use kartik\editable\Editable;
use yii\bootstrap\Modal;
use common\models\procurement\Lineitembudgetobjectrealignment;
use common\models\procurement\Linetembudgetobjectdetailsrealignment;
?>

<div class="table-scroll" style="backiground: #f6f6f6;">


<?php
echo GridView::widget([
    'dataProvider'=> $dataProvider,
    //'filterModel' => $searchModel,
    'id' => 'lineitembudget-details-'.$model["line_item_budget_object_id"],
    'bordered' => true,
    'pjax' => true,
    'export' =>false,
    'panel' => [
        'heading' => '<b>Object Details</b>',
        'type' => Gridview::TYPE_INFO,
    ],
    'toolbar' =>
        [
            '{export}',
            '{toggleData}'
        ],
    'columns' => [
        [
            'attribute'=>'quantity',
            'width'=>'25px',
            'label'=>'Qty',
            'contentOptions' => ['style' => 'text-align: center'],
        ],
        [
            'attribute'=>'objectDetail.name',
            'width'=>'100px',
            'contentOptions' => ['style' => 'max-width:100px;white-space:normal;word-wrap:break-word;font-size:11px;font-weight:bold'],
            'value'=>function ($model, $key, $index, $widget) {
                return $model->objectDetail->name;
            },
        ],
        [
            'attribute'=>'details',
            'width'=>'100px',
            'label'=>'Details',
            'contentOptions' => ['style' => 'max-width:100px;white-space:normal;word-wrap:break-word;font-size:11px;font-weight:bold'],
        ],
        [
            'attribute'=>'amount',
            'width'=>'25px',
            'contentOptions' => ['style' => 'max-width:25px;white-space:normal;word-wrap:break-word;font-size:11px;font-weight:bold'],
            'format'=>['decimal', 2],
        ],
        [
            'class' => 'kartik\grid\CheckboxColumn',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'checkboxOptions' => function($model, $key, $index, $column) {
            }
        ],



    ],
    'containerOptions' => ['style' => 'overflow: auto; margin: 20px;'], // only set when $responsive = false
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'pjax' => true, // pjax is set to always true for this demo
    'responsive'=>true,
    'hover'=>true,
]);
?>

</div>