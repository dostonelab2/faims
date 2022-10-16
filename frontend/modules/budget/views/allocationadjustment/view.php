<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $model common\models\budget\Allocationadjustment */

//$this->title = $model->allocation_adjustment_id;
$this->params['breadcrumbs'][] = ['label' => 'Allocationadjustments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="allocationadjustment-view">

    <h1><?= Html::encode($this->title) ?></h1>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'id'=>'expenditure_objects', //additional
        'containerOptions'=> ["style"  => 'overflow:auto;height:300px'],
        'pjax' => true, // pjax is set to always true for this demo
                'pjaxSettings' => [
                        'options' => [
                            'enablePushState' => false,
                        ]
                    ],
        'floatHeaderOptions' => ['scrollingTop' => true],
        'panel' => [
            'heading'=>'<h3 class="panel-title">Adjustments</h3>',
            'type'=>'success',

         ],
        'showPageSummary' => true,
        'toolbar'=> [],
        'columns' => [

            [
                'attribute' => 'item_id',
                'value'=>function ($model, $key, $index, $widget){ 
                            return $model->budgetAllocationItem->name;
                        },
            ],
            [
                'attribute' => 'item_id',
                'header' => 'Source Item',
                'value'=>function ($model, $key, $index, $widget){ 
                            if($model->source_item)
                                return $model->sourceItem->name.' - '.$model->sourceSection;
                                //return $model->source_item;
                            else
                                return '-';
                        },
            ],
            [
                'attribute' => 'amount',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: right; padding-right: 10px;'],
                'value'=>function ($model, $key, $index, $widget){ 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal($model->amount);
                        },
            ],
    ]]); 
?>

</div>
