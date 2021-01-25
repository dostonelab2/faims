<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurementplan\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => false,
        'columns' => [
            [
                'class' => 'kartik\grid\SerialColumn',
                'contentOptions' => ['class' => 'kartik-sheet-style'],
            ],
            [
                'attribute'=>'availability',
                'header'=>'Category',
                //'visible' => $ppmpItemsDataProvider->totalCount > 0 ? true : false,
                'value'=>function ($model, $key, $index, $widget) { 
                        if($model->availability == 1){
                            return 'PART I. AVAILABLE AT PROCUREMENT SERVICE STORES';
                        }elseif($model->availability == 2){
                            return 'PART II. OTHER ITEMS NOT AVAILABLE AT PS BUT REGULARLY PURCHASED FROM OTHER SOURCES (Note: Please indicate price of items)';
                        }
                    },
                'headerOptions' => ['style' => 'background-color: #fee082;'],
                'contentOptions'=>['style'=>'background-color: #fee082; font-weight: bold;'],
            
                'group'=>true,  // enable grouping,
                'groupedRow'=>true,                    // move grouped column to a single grouped row
                //'contentOptions' => ['style' => 'text-align: left; background-color: #ffe699;'],
                
                'groupOddCssClass'=>'',  // configure odd group cell css class
                'groupEvenCssClass'=>'', // configure even group cell css class
            ],
            [
                'attribute'=>'item_category_id',
                'header'=>'Category',
                //'visible' => $ppmpItemsDataProvider->totalCount > 0 ? true : false,
                'width'=>'100px',
                'value'=>function ($model, $key, $index, $widget) { 
                        return $model->itemcategory->category_name;
                    },
                'headerOptions' => ['style' => 'text-align: left; background-color: #7e9fda;'],
                'contentOptions' => ['style' => 'text-align: left; background-color: #7e9fda;'],
            
                'group'=>true,  // enable grouping,
                'groupedRow'=>true,                    // move grouped column to a single grouped row
                'groupOddCssClass'=>'',  // configure odd group cell css class
                'groupEvenCssClass'=>'', // configure even group cell css class
            ],
            
            'item_id',
            //'item_category_id',
            'item_code',
            [
                'attribute' => 'item_name',
                'value' => function($model){
                    if(strlen($model->item_name) > 50){
                        return substr($model->item_name,0,50).'...';
                    }else{
                        return $model->item_name;
                    }
                }

            ],
            [
                'attribute' => 'unit_of_measure_id',
                'value'=>function ($model, $key, $index, $widget){ 
                            return $model->getUnit();
                        },
            ],
            [
                'class'=>'kartik\grid\EditableColumn',
                'attribute' => 'price_catalogue',
                'refreshGrid'=>true,
                'readonly' => false,
                'value' => function($model){
                    $fmt = Yii::$app->formatter;
                    return $fmt->asDecimal($model->price_catalogue);
                },
                'editableOptions'=> function ($model , $key , $index) {
                    return [
                        //'options' => ['id' => $key . '_' . $model->item_id . '-price'],
                        'placement'=>'left',
                        //'disabled'=>($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                        'name'=>'q1',
                        'asPopover' => true,
                        'value' => $model->price_catalogue,
                        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                        'formOptions'=>['action' => ['/procurementplan/item/updateprice']], // point to the new action
                    ];
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',    
                'buttons' => [
                    'view' => function ($url,$model)
                    {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',['view','id' => $model->item_id],['class' => 'btn btn-success btn-sm','data-toggle' => 'tooltip', 'title' => 'view']);
                            //return Html::a('<button type="button" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-eye-open"></span></button>',$url);  
                            //return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['href' => Url::to($url),'class' => 'btn btn-success btn-sm']); 
                    },
                    'update' => function ($url,$model)
                    {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>',['update','id' => $model->item_id],['class' => 'btn btn-info btn-sm','data-toggle' => 'tooltip', 'title' => 'update']);
                    },
                    'delete' => function ($url,$model)
                    {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>',['delete','id' => $model->item_id],
                            [
                                'class' => 'btn btn-danger btn-sm',
                                'data-toggle' => 'tooltip',
                                'title' => 'delete',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post'
                                ]
                            ]);
                    },
                ], 
            ],
        ],
    ]); ?>
</div>
