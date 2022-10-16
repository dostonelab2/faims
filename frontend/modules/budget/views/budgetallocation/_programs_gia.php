<?php
use kartik\grid\GridView;
use kartik\select2\Select2;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ActiveForm;

use common\models\budget\Budgetallocationitem;
use common\models\procurement\Expenditure;
use common\models\procurement\Fundsource;
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
    div#item-details {
        width: 935px;
        padding-left: 43px;
        padding-right: 10px;
    }
</style>
<div id='item-details'>
<div style="margin-bottom: 10px;">    
    <?php 
    echo Html::button('Add Programs  <i class="glyphicon glyphicon-tasks"></i>', ['value' => Url::to(['budgetallocationitemdetails/additemdetails', 'id'=>$budgetAllocationItemId, 'year'=>1, 'program'=>'gia']), 'title' => 'Add Budget Allocation Items', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonAddBudgetallocationItem']);
    ?>
</div>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'id'=>'program-items-gia', //additional
        'pjax' => true, // pjax is set to always true for this demo
                'pjaxSettings' => [
                        'options' => [
                            'enablePushState' => false,
                        ]
                    ],
        'columns' => [
            [
                'attribute' => 'name',
                'value'=>function ($model, $key, $index, $widget){ 
                            return $model->name . ' (+) ';
                        },
            ],
            [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'fund_source_id',
                    'header'=>'Source of Fund',
                    'width'=>'250px',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'value'=>function ($model, $key, $index, $widget) { 
                            return $model->fundsource->code;
                        },
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->budget_allocation_item_detail_id . '_' . $model->fund_source_id],
                            'placement'=>'left',
                            //'disabled'=>($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'fund_source_id',
                            'asPopover' => true,
                            'value' => $model->fundsource->code,
                            'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                            'data' => ArrayHelper::map(Fundsource::find()->asArray()->all(), 'fund_source_id', 'code'),
                            'formOptions'=>['action' => ['/budget/budgetallocationitemdetails/updatefundsource']], // point to the new action
                        ];
                    },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
            [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'amount',
                    'header'=>'Fund Allocation',
                    'width'=>'250px',
                    'refreshGrid'=>true,
                    //'readonly' => !$isMember,
                    'value'=>function ($model, $key, $index, $widget) { 
                            $fmt = Yii::$app->formatter;
                            return $fmt->asDecimal($model->amount);
                        },
                    'editableOptions'=> function ($model , $key , $index) {
                        return [
                            'options' => ['id' => $index . '_' . $model->budget_allocation_item_detail_id],
                            'placement'=>'left',
                            //'disabled'=>($model->ppmp->status_id != Ppmp::STATUS_PENDING),
                            'name'=>'amount',
                            'asPopover' => true,
                            'value' => $model->amount,
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'formOptions'=>['action' => ['/budget/budgetallocationitemdetails/updateamount']], // point to the new action
                        ];
                    },
                    'headerOptions' => ['style' => 'text-align: center'],
                    'hAlign'=>'right',
                    'vAlign'=>'left',
                    'width'=>'100px',
                ],
    ]]); 
?>
</div>
<br/>