<?php
use kartik\grid\GridView;
use kartik\select2\Select2;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ActiveForm;

use common\models\cashier\Creditortype;
use common\models\finance\Requestpayroll;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id'=>'accounts', //additional
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
                'name'=>'account', //additional
                'checkboxOptions' => function($model, $key, $index, $column) use ($id){
                                         $bool = Requestpayroll::find()->where(['osdv_id'=>$id, 'creditor_id'=>$model->creditor_id,'active'=>1])->count();
                                         return ['checked' => $bool,
                                                'onclick'=>'onAdditem('.$id.',this.value, this.checked)' //additional
                                                //'onclick'=>'alert(this.value)' //additional
                                                ];
                                     },
                'width'=>'10%',
            ],
            //'creditor_id',
            [
                'attribute' => 'creditor_type_id',
                //'width'=>'100px',
                'headerOptions' => ['style' => 'text-align: center; width: 50%;'],
                'value'=>function ($model, $key, $index, $widget) { 
                            return $model->type->name;
                        },
                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Creditortype::find()->asArray()->all(), 'creditor_type_id', 'name'), 
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],  
                'filterInputOptions' => ['placeholder' => 'Select Creditor Type']
            ],
            [
                'attribute' => 'name',
                //'width'=>'100px',
                'headerOptions' => ['style' => 'text-align: center; width: 50%;'],
                'value'=>function ($model, $key, $index, $widget) { 
                            return $model->name;
                        },
                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Creditortype::find()->asArray()->all(), 'creditor_type_id', 'name'), 
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],  
                'filterInputOptions' => ['placeholder' => 'Select Creditor Type']
            ],
            [
                'attribute' => 'account_number',
                'value'=>function ($model, $key, $index, $widget) { 
                            return $model->account_number;
                        },
                'headerOptions' => ['style' => 'text-align: center; width: 40%;'],
            ],
    ]]); 
?>

<div class="clearfix"></div>


<script type="text/javascript">
function onAdditem(id,creditorid,checked){
    $.ajax({
            type: "POST",
            url: "<?php echo Url::to(['requestpayroll/additem']); ?>",
            data: {id: id, creditorid: creditorid, checked: checked},
            success: function(data){ 
                }
            });

    return false;
}    
</script>


