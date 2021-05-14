<?php
use kartik\grid\GridView;
use kartik\select2\Select2;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ActiveForm;

use common\models\cashier\Lddapadaitem;
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
        'panel' => [
                'heading' => '',
                'type' => GridView::TYPE_SUCCESS,
                //'before'=> Html::button('Add Creditors', ['value' => Url::to(['request/payrollitems', 'id'=>$model->request_id]), 'title' => 'Submit', 'class' =>'btn btn-success', 'style'=>'margin-right: 6px;'.((($model->status_id < Request::STATUS_SUBMITTED)) ? ($model->attachments ? '' : 'display: none;') : 'display: none;'), 'id'=>'buttonPayrollitems']) ,
                'before'=> Html::button('Add Items', ['value' => Url::to(['osdv/requestpayrollitems', 'request_payroll_id'=>$model->request_payroll_id, 'osdv_id'=>$model->osdv_id]), 'title' => 'Submit', 'class' =>'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonRequestpayrollitems']) ,
                'after'=>false,
            ],
    
    'columns' => [
            [
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '20px',
                    'header' => '',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'pageSummary'=>'Total',
                    'pageSummaryOptions' => ['colspan' => 2],
                ],
            [
                'attribute' => 'name',
                'value'=>function ($model, $key, $index, $widget){ 
                            //return $model->creditor_id . ' (+) ';
                            return $model->creditor->name;
                        },
            ],
            [
                'attribute' => 'dv_id',
                'header' => 'DV Number',
                'value'=>function ($model, $key, $index, $widget){ 
                            return $model->requestpayroll->dv ? $model->requestpayroll->dv->dv_number : '-';
                        },
            ],
            /*[
                'attribute' => 'amount',
                'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'contentOptions' => ['style' => 'text-align: right; padding-right: 25px; vertical-align: middle;'],
                'format' => ['decimal',2],
                'value'=>function ($model, $key, $index, $widget){ 
                            return $model->amount;
                        },
            ],*/
            [
                'class'=>'kartik\grid\EditableColumn',
                'attribute'=>'amount',
                'header'=>'Gross Amount',
                'width'=>'450px',
                'refreshGrid'=>true,
                'format'=>['decimal',2],
                //'readonly' => !$isMember,
                'value'=>function ($model, $key, $index, $widget) { 
                        return $model->amount;
                    },
                'editableOptions'=> function ($model , $key , $index) {
                    return [
                        'options' => ['id' => $index . '_' . $model->request_payroll_item_id],
                        'placement'=>'left',
                        'disabled'=>!Yii::$app->user->can('access-finance-disbursement'),
                        //'disabled'=>true,
                        'name'=>'amount',
                        'asPopover' => true,
                        'value' => $model->amount,
                        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                        'formOptions'=>['action' => ['/finance/requestpayrollitem/updateamount']], // point to the new action
                    ];
                },
                'headerOptions' => ['style' => 'text-align: center'],
                'contentOptions' => ['style' => 'padding-right: 20px;'],
                'hAlign'=>'right',
                'vAlign'=>'left',
                'width'=>'250px',
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
                'pageSummaryOptions' => ['style' => 'text-align: right; padding-right: 25px;'],
            ],
    ]]); 
?>
</div>
<br/>
<script>
//function onCreditorpayroll(osdv_id,checked){
/*function onCreditorpayroll(payroll_id,checked){
    var lddapada_id = <?php //echo $osdv_id?>;
    $.ajax({
            type: "POST",
            url: "<?php //echo Url::to(['lddapada/addpayrollitems']); ?>",
            data: {payrollId:payroll_id,lddapadaId:lddapada_id,checked:checked},
            success: function(data){ 
                }
            });

    return false;
}*/
</script>