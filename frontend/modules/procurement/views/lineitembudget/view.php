<?php

use yii\bootstrap\Modal;
use kartik\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView; 
use kartik\editable\Editable;
use yii\data\ActiveDataProvider;
use common\models\procurement\Lineitembudgetobjectdetails;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Lineitembudget */


$BaseURL = $GLOBALS['frontend_base_uri'];
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lineitembudgets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile($BaseURL.'js/jquery.tabletojson.js');


// Modal Object
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    //'headerOptions' => ['id' => 'modalHeader'],
    'id' => 'modalObject',
    'size' => 'modal-lg',
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();


// Modal LIB
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    //'headerOptions' => ['id' => 'modalHeader'],
    'id' => 'modalLib',
    'size' => 'modal-lg',
]);
echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

?>


<div class="lineitembudget-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->line_item_budget_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->line_item_budget_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    
    <?php
    $attributes = [
        [
            'group'=>true,
            'label'=>'LIB DETAILS',
            'rowOptions'=>['class'=>'info']
        ],
        [
                    'attribute'=>'title', 
                    'label'=>'Title',
                    'labelColOptions'=>['style'=>'width:10%'],
                    'displayOnly'=>true,
                    'format'=>'raw', 
                    'value'=>'<kbd>'.$model->title.'</kbd>',
        ],
        [
                    'attribute'=>'division_id', 
                    'label'=>'Division',
                    'labelColOptions'=>['style'=>'width:10%'],
                    'displayOnly'=>true,
                    'format'=>'raw', 
                    'value'=>$model->division->name ,
        ],
        [
                    'attribute'=>'section_id', 
                    'label'=>'Section',
                    'labelColOptions'=>['style'=>'width:10%'],
                    'displayOnly'=>true,
                    'format'=>'raw', 
                    'value'=>$model->section->name ,
        ],
        [
            'group'=>true,
            'label'=>'APPLICABLE CHARGES',
            'rowOptions'=>['class'=>'info']
        ],
    ];
    
    
    // View file rendering the widget
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
        'mode' => 'view',
        'bordered' => true,
        'striped' => false,
        'condensed' => true,
        'responsive' => true,
        'hover' => true,
        'container' => ['id'=>'kv-demo'],
    ]);
    ?>
    
    <?php echo GridView::widget([
                        'dataProvider'=> $dataProvider,
                        //'filterModel' => $searchModel,
                        'id' => 'lineitembudget-objects',
                        'bordered' => true,
                        'pjax' => true,
                        'export' =>false,
                        'panel' => [
                            'heading' => '<b>Expenditure Object</b>',
                            'type' => Gridview::TYPE_PRIMARY,
                        ],
                        'toolbar' => 
                        [
                            [
                                'content'=>
                                Html::button('Realignment LIB Objects', ['value' => Url::to(['lineitembudget/updaterealignment?id='.$_GET['id']]), 'title' => 'Expenditure Class', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddLibObject']) .
                                Html::button('Add LIB Objects Details', ['value' => Url::to(['lineitembudget/updateobjects?id='.$_GET['id']]), 'title' => 'Expenditure Class', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddLibObject']) .
                                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['view','id'=>$_GET['id']], ['data-pjax' => false, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
                            ],
                            '{export}',
                            '{toggleData}'
                        ],
                        'columns' => [
                            [
                                'attribute'=>'expenditureObject.expenditureSubClass.ExpenditureClass.name', 
                                'contentOptions' => ['style' => 'font-weight: bold; text-decoration: underline;'],
                                'width'=>'5px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->expenditureObject->expenditureSubClass->expenditureClass->name;
                                },
                                'group'=>true,  // enable grouping,
                                'groupedRow'=>true,                    // move grouped column to a single grouped row
                                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                            ],
                            [
                                'attribute'=>'expenditureObject.expenditureSubClass.name', 
                                'width'=>'5px',
                                'contentOptions' => ['style' => 'padding-left: 30px'], 
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->expenditureObject->expenditureSubClass->name;
                                },
                                'group'=>true,  // enable grouping,
                                'groupedRow'=>true,                    // move grouped column to a single grouped row
                                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                            ],
                            [
                                'class' => 'kartik\grid\ExpandRowColumn',
                                'width' => '5px',
                                'value' => function ($model, $key, $index, $column) {
                                    return GridView::ROW_COLLAPSED;
                                },
                                'detail' => function ($model, $key, $index, $column) {


                                    $query = Lineitembudgetobjectdetails::find()->where(['line_item_budget_object_id' => $model->line_item_budget_object_id ]);
                                    
                                    $dataProvider = new ActiveDataProvider([
                                        'query' => $query,
                                        'pagination' => false,
                                    ]);

                                    return Yii::$app->controller->renderPartial('_object-details', ['model' => $model, 'dataProvider'=>$dataProvider]);
                                },
                                'headerOptions' => ['class' => 'kartik-sheet-style'],
                                'expandOneOnly' => true
                            ],
                            [
                                'attribute'=>'expenditureObject.name', 
                                'width'=>'250px',
                                'contentOptions' => ['style' => 'padding-left: 60px'], 
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->expenditureObject->name;
                                },
                            ],
                            [
                                'class'=>'kartik\grid\EditableColumn',
                                'attribute'=>'amount',
                                'value' => function ($model, $key, $index, $widget) {
                                    $fmt = Yii::$app->formatter;
                                    return $model->lineItemBudgetObjectDetails ? $model->details : $fmt->asDecimal($model->amount,2);
                                },
                                'editableOptions'=>[
                                    'placement'=>'left',
                                    'header'=>'Amount',
                                    'formOptions'=>['action' => ['lineitembudget/editLibObjects']], // point to the new action
                                    //'formOptions'=>['action' => ['lineitembudget/editamount']], // point to the new action
                                    'inputType' => \kartik\editable\Editable::INPUT_MONEY,
                                        'options' => [
                                            'pluginOptions' => ['min' => 0, 'max' => 10000000,'prefix'=>'PHP ','decimals'=>2, 'decPoint'=>'.', 'thousandSep'=>',','format'=>'number'],
                                            //'format'=>'number',
                                        ]
                                ],
                                'readonly' => function ($model, $key, $index, $widget) { 
                                    return $model->lineItemBudgetObjectDetails ? true : false;
                                },
                                'hAlign'=>'right',
                                'vAlign'=>'middle',
                                'width'=>'200px',
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
