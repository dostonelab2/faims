<?php
use kartik\helpers\Html;
use yii\helpers\Url;

use kartik\grid\GridView;
use kartik\editable\Editable;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use common\models\procurement\Lineitembudgetobjectdetailsrealignment;
use common\models\procurement\Lineitembudgetobject;

?>

<div onload="hello()"></div>


<?php

echo \kartik\grid\GridView::widget([

    'dataProvider'=> $dataProvider2,
    'id' => 'realignment-objects2',
    'bordered' => true,
    'striped' => false,
    'condensed' => true,
    'pjax' => true,
    'pjaxSettings' => [
        'options' => [
            'enablePushState' => true,
        ],
    ],
    'toolbar' =>
        [
            [
                'content'=>
                    //Html::button('New Line-Item Budget', ['value' => Url::to(['lineitembudget/create']), 'title' => 'Expenditure Class', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddLibObject']) .
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['updaterealignment?id='.$id], ['data-pjax' => true, 'id'=> 'btnrefresh' , 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
            ],
           // '{export}',
           //'{toggleData}'
        ],
    'panel' => [
        'heading' => '<b>Re Alignment Object</b>',
        'type' => \kartik\grid\Gridview::TYPE_PRIMARY,
    ],
    'columns' => [
        [
            'class' => 'kartik\grid\ExpandRowColumn',
            'width' => '5px',
            'value' => function ($model, $key, $index, $column) {
                return \kartik\grid\GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
                $lib = $model["line_item_budget_object_id"];
                $model = Lineitembudgetobject::findOne($lib);
                $query = Lineitembudgetobjectetailsrealignment::find()->where(['line_item_budget_object_id' =>  $lib]);
                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => false,
                ]);
                return Yii::$app->controller->renderPartial('_realign_objectdetails', ['model' => $model, 'dataProvider'=>$dataProvider]);
            },
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'expandOneOnly' => true
        ],
        [
            'attribute'=>'name',
            'width'=>'100px',
        ],
        [
            'attribute'=>'object_code',
            'width'=>'250px',
        ],
        [
            'class'=>'kartik\grid\EditableColumn',
            'attribute'=>'amount',
            'readonly' => function($model, $key, $index, $widget) {
                //return ($model->purchase_request_details_status==1); // do not allow editing of inactive records
            },
            'refreshGrid'=>true,
            'editableOptions'=> function ($model , $key , $index) {
                $fmt = Yii::$app->formatter;
                return [
                    'placement'=>'left',
                    'name'=>'amount',
                    'header'=>'Amount',
                    'asPopover' => true,
                    'value' =>  floatval($model["amount"]),
                    'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                    'formOptions'=>['action' =>  Url::toRoute(['/procurement/lineitembudget/editamount'])],
                    'displayValue' => $fmt->asDecimal($model["amount"],2) ,
                        'options'=>['pluginOptions'=>['min'=>0, 'max'=>1000000000,'step'=>100,'decimal'=>2 ,'prefix'=>'PHP']]
                ];
            },
            'hAlign'=>'right',
            'vAlign'=>'left',
            'width'=>'100px',
        ],

        [
            'class' => '\kartik\grid\ActionColumn',
            'template'=> "{delete}",
            //'refreshGrid' => true,
            'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>'],
            'buttons' =>
                [
                    'delete'=>function($url, $model){
                        return Html::a("<span class='glyphicon glyphicon-trash'></span>", $url, [
                            "title"=>"Delete",
                            "aria-label"=>"Delete",
                            "data-pjax"=>"1",
                            "data-method"=>"post",
                            "data-confirm"=>"Are you sure you want to delete?",
                            "class"=>"btn btn-danger"
                        ]);
                    }
                ],
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'delete') {
                    $url = Url::toRoute(['/procurement/lineitembudget/deletealignment','id' => $model["line_item_budget_object_realignment_id"] , 'mainid' => $model["line_item_budget_id"] ]);
                    return $url;
                }
            }
        ],
    ],
    'containerOptions' => ['style' => 'overflow: auto; margin: 20px;'],
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'responsive'=>true,
    'hover'=>true,
]);

echo $this->registerJs(
    'function hello(){
	alert(\'Hello world! in func hello\');
}
$(function(){
	$(\'div[onload]\').trigger(\'onload\');
});');
