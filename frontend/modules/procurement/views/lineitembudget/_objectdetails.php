<?php
use kartik\helpers\Html;
use yii\helpers\Url;

use kartik\grid\GridView;
use kartik\editable\Editable;
use yii\bootstrap\Modal;
use common\models\procurement\Lineitembudgetobjectrealignment;
use common\models\procurement\Lineitembudgetobjectdetailsrealignment;
?>

<div class="table-scroll" style="background: #f6f6f6;">


<?php

echo GridView::widget([
    'dataProvider'=> $dataProvider,
    //'filterModel' => $searchModel,
    //'id' => 'realignment-objects',
    'bordered' => true,
    'pjax' => true,
    'export' =>false,
    'panel' => [
        'heading' => '<b>Object Details</b>',
        'type' => Gridview::TYPE_INFO,
    ],
    'toolbar' =>
        [
            /*[
                    'content'=>
                    Html::button('Add Object Details',   ['value' => Url::to(['lineitembudgetobjectdetails/create', 'id'=>$model->line_item_budget_object_id]), 'title' => 'Object Details', ['data-pjax' => true], 'class' => 'btn btn-PRIMARY', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddDetails'])
            ],
            */
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
                $lib = $model["line_item_budget_object_id"];
                $bool = Lineitembudgetobjectrealignment::find()->where(['line_item_budget_object_id'=> $lib])->count();
                $bool2 = Lineitembudgetobjectdetailsrealignment::find()->where(['line_item_budget_object_id'=> $lib])->count();
                $dsb = $bool==0 ? true : false;
                if ($bool==1 && $bool2==1) {
                    $dsb = true;
                }
                return ['disabled'=>$dsb,'data' => ['key' => $model["line_item_budget_object_id"]],'value'=> $model["line_item_budget_object_id"]];
            }
        ],


        [
            'class' => 'kartik\grid\DataColumn',
            'format'=>'html',
            'header'=>'Remarks',
            'width'=>'25px',
            'value'=>function($model,$key,$index,$widget) {
                $lib = $model["line_item_budget_object_id"];
                $bool = Lineitembudgetobjectdetailsrealignment::find()->where(['line_item_budget_object_id'=> $lib])->count();
                $dsb = $bool==0 ? true : false;
                switch ($dsb) {
                    case 0:
                        return '<span class="badge btn-block" style="background: blue;">Aligned <i class="fa fa-check-circle"></i></span>';
                        break;
                    case 1:
                        return '<span class="badge btn-block" style="background: green;">Available <i class="fa fa-check"></i></span>';
                        break;
                }
            },
            'headerOptions' => ['class' => '','style'=>'color:#337ab7;'],
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