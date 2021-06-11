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
        'columns' => [
            [
                    'class' => '\kartik\grid\CheckboxColumn',
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'name'=>'lddap-ada-payroll-items', //additional
                    'checkboxOptions' => function($model, $key, $index, $column) use ($id){
                                         //if($model->osdv_id == $_GET['id']){
                                                $bool = Lddapadaitem::find()->where(['request_payroll_id' => $model->request_payroll_id, 'osdv_id'=>$model->osdv_id, 'active'=>1])->count();
                                                 if($bool){
                                                    $exist = Lddapadaitem::find()->where(['request_payroll_id' => 24, 'lddapada_id'=>$id])->count();
                                                    return ['checked' => $bool, 'onclick'=>'onCreditorpayroll(this.value,this.checked)', 'disabled'=>($exist ? false : true)];
                                                 }else{
                                                    return ['disabled'=>false];
                                                 }
                    }
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
                'value'=>function ($model, $key, $index, $widget){ 
                            return $model->dv->dv_number;
                        },
            ],
            [
                'attribute' => 'amount',
                'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'contentOptions' => ['style' => 'text-align: right; padding-right: 25px; vertical-align: middle;'],
                'format' => ['decimal',2],
                'value'=>function ($model, $key, $index, $widget){ 
                            return $model->amount;
                        },
            ],
    ]]); 
?>
</div>
<br/>
<script>
//function onCreditorpayroll(osdv_id,checked){
function onCreditorpayroll(payroll_id,checked){
    var lddapada_id = <?php echo $id?>;
    $.ajax({
            type: "POST",
            url: "<?php echo Url::to(['lddapada/addpayrollitems']); ?>",
            data: {payrollId:payroll_id,lddapadaId:lddapada_id,checked:checked},
            success: function(data){ 
                }
            });

    return false;
  //});
}
</script>