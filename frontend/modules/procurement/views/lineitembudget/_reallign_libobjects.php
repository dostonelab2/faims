<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\grid\GridView;

use yii\data\ActiveDataProvider;
use common\models\procurement\Lineitembudgetobjectdetails;
use common\models\procurement\Lineitembudgetobject;
use common\models\procurement\Lineitembudgetobjectrealignment;
use common\models\procurement\Lineitembudgetobjectdetailsrealignment;
$BaseURL = $GLOBALS['frontend_base_uri'];


?>

<div class="table-scroll" style="height: 800px!important;background: #f6f6f6;">
<div class="row">


    <div class="col-lg-5" style="padding-left: 1.35%!important;">
        <div class="spacing-20"></div>
        <?php
        echo \kartik\grid\GridView::widget([
                        'dataProvider'=> $dataProvider,
                        'id' => 'realignment-objects',
                        'bordered' => true,
                        'export'=>false,
                        'rowOptions' => function ($model, $key, $index, $grid) {
                            return [
                                'tr data-key' => $model["line_item_budget_object_id"],
                            ];
                        },
                        'pjaxSettings' => [
                            'neverTimeout'=> false,
                            'options' => [
                                'enablePushState' => true,
                                'id'=>'realignment-objects-pjax',
                            ],
                        ],
                        'panel' => [
                            'heading' => '<b>Line Item Object</b>',
                            'type' => \kartik\grid\GridView::TYPE_PRIMARY,
                        ],

                        'toolbar' =>
                            [
                                [
                                    'content'=>
                                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['updaterealignment?id='.$id], ['data-pjax' => true, 'id'=> 'btnrefresh2' , 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
                                ],
                                // '{export}',
                                //'{toggleData}'
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
                                                $query = Lineitembudgetobjectdetails::find()->where(['line_item_budget_object_id' => $model->line_item_budget_object_id]);
                                                $model_realign = new Lineitembudgetobjectdetailsrealignment();
                                                $dataProvider = new ActiveDataProvider([
                                                     'query' => $query,
                                                     'pagination' => false,
                                                 ]);
                                                  return Yii::$app->controller->renderPartial('_objectdetails', ['model' => $model, 'dataProvider'=>$dataProvider
                                                  ,'model_realign' => $model_realign,]);
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
                                            'class' => 'kartik\grid\CheckboxColumn',
                                            'attribute'=> $model["line_item_budget_object_id"],
                                            'headerOptions' => ['class' => 'kartik-sheet-style'],
                                            'checkboxOptions' => function($model, $key, $index, $column){
                                                $lib = $model["line_item_budget_object_id"];
                                                $bool = Lineitembudgetobjectrealignment::find()->where(['line_item_budget_object_id'=> $lib])->count();
                                                $dsb = $bool==0 ? false : true;
                                                return ['disabled'=>$dsb,'data' => ['key' => $model["line_item_budget_object_id"]],'value'=> $model["line_item_budget_object_id"]];
                                            }
                                        ],                                    

                            [
                                'class' => 'kartik\grid\DataColumn',
                                'format'=>'html',
                                'header'=>'Remarks',
                                'value'=>function($model,$key,$index,$widget) {
                                    $lib = $model["line_item_budget_object_id"];
                                    $bool = Lineitembudgetobjectrealignment::find()->where(['line_item_budget_object_id'=> $lib])->count();
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

    <div class="col-lg-1" style="text-align: center;margin-top: 10%;margin-bottom: 10%;">

                <a class="btn btn-success" id="btnForward"><img id="leftLoad" src="<?php echo $BaseURL.'/'; ?>images/loading.gif" style="filter: brightness(0) invert(1);display:none;height: 50px;width: 50px;">
                    <i id="leftIcon" class="glyphicon glyphicon-chevron-right" style="width: 50px;"> (<span id="counts" style="color: #fff9e5;font-weight: bold;font-size:small">0</span>)</i></a>
    </div>

    <div class="col-lg-6" style="padding-right: 1.5%!important;">
        <div class="spacing-20"></div>
        <?php
        echo \kartik\grid\GridView::widget([

            'dataProvider'=> $dataProvider2,
            'id' => 'realignment-objects2',
            'bordered' => true,
            'striped' => false,
            'condensed' => true,
            'pjax' => true, // pjax is set to always true for this demo
            'pjaxSettings' => [
                'options' => [
                    'enablePushState' => true,
                ],
            ],
            'panel' => [
                'heading' => '<b>Re Alignment Object</b>',
                'type' => \kartik\grid\Gridview::TYPE_PRIMARY,
            ],

            'toolbar' =>
                [
                    [
                        'content'=>
                        //Html::button('New Line-Item Budget', ['value' => Url::to(['lineitembudget/create']), 'title' => 'Expenditure Class', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddLibObject']) .
                            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['updaterealignment?id='.$id], ['data-pjax' => true, 'id'=> 'btnrefresh' , 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
                    ],
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
                        $query = Lineitembudgetobjectdetailsrealignment::find()->where(['line_item_budget_object_id' =>  $lib]);
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
                        'format'=>'decimal',
                        'asPopover' => true,
                        'value' =>   floatval($model["amount"]),
                        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                        'formOptions'=>['action' => ['/procurement/lineitembudget/editamount']], // point to the new action
                        'displayValue' =>  $fmt->asDecimal($model["amount"]) ,
                        'options'=>[ 'pluginOptions' => ['min' => 0, 'max' => 10000000,'prefix'=>'PHP ','decimals'=>2, 'decPoint'=>'.', 'thousandSep'=>',','format'=>'number']]
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
                            $url ='deletealignment?id='.$model["line_item_budget_object_realignment_id"].'&mainid='.$model["line_item_budget_id"];
                            return $url;
                        }
                    }
                ],
            ],
            'containerOptions' => ['style' => 'overflow: auto; margin: 20px;'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'responsive'=>true,
            'hover'=>true,
        ]);
        ?>
    </div>
  </div>
</div>
