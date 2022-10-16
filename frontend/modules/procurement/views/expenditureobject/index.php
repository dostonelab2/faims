<?php

use yii\helpers\Html;
use kartik\editable\Editable; 
use kartik\grid\GridView;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use common\models\procurement\Expenditureobject;

/* @var $this yii\web\View */
/* @var $searchModel common\models\cashier\CreditorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Expenditure Objects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expenditureobject-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <!--?= Html::a('Create Creditor', ['create'], ['class' => 'btn btn-success']) ?-->
    </p>
    
          <?php
        echo GridView::widget([
            'id' => 'request',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'containerOptions' => ['style' => 'overflow-x: none!important','class'=>'kv-grid-container'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'bordered' => true,
            'striped' => true,
            'condensed' => true,
            'responsive' => false,
            'hover' => true,
            'columns' => [

                            [
                                'attribute'=>'expenditure_object_id',
                                'header'=>'Name',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: left; padding-left: 10px;'],
                                'width'=>'150px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->name;
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Expenditureobject::find()->asArray()->all(), 'expenditure_object_id', 'name'), 
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],  
                                'filterInputOptions' => ['placeholder' => 'Select Expenditure Object']
                            ],
                            
                            [
                                'class'=>'kartik\grid\EditableColumn',
                                'attribute'=>'object_code',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'editableOptions'=>[
                                    //'options' => ['id' =>'_10_' . $model->object_code],
                                    'header'=>'Object Code',
                                    'placement'=>'left',
                                    'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                                    'formOptions'=>['action' => ['/procurement/expenditureobject/updateobjectcode']], // point to the new action
                                ],
                                'hAlign'=>'right',
                                'vAlign'=>'middle',
                                'width'=>'100px',
                                'pageSummary'=>true
                            ],
                
                            [
                                'class'=>'kartik\grid\EditableColumn',
                                'attribute'=>'account_code',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'editableOptions'=>[
                                    //'options' => ['id' => '_20_' . $model->account_code],
                                    'header'=>'Account Code',
                                    'placement'=>'left',
                                    'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                                    'formOptions'=>['action' => ['/procurement/expenditureobject/updateobjectcode']], // point to the new action
                                ],
                                'hAlign'=>'right',
                                'vAlign'=>'middle',
                                'width'=>'100px',
                                'pageSummary'=>true
                            ],
                    ],
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'panel' => [
                    'heading' => '',
                    'type' => GridView::TYPE_PRIMARY,
                    'before'=> '',
                    'after'=>false,
                ],
            'pjax' => true, // pjax is set to always true for this demo
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
