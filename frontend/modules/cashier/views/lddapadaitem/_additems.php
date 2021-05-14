<?php
use kartik\grid\GridView;
use kartik\select2\Select2;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use yii\helpers\Url;

use yii\widgets\ActiveForm;

use common\models\cashier\Lddapadaitem;
/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\Ppmpitem */
/* @var $form yii\widgets\ActiveForm */
?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id'=>'ppmp_items', //additional
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
                'name'=>'lddap-ada-items', //additional
                'checkboxOptions' => function($model, $key, $index, $column) use ($lddapada_id){
                                         $bool = Lddapadaitem::find()->where(['lddapada_id' => $lddapada_id, 'creditor_id' => $model->request->creditor, 'active'=>1])->count();
                                         //$bool = Lddapadaitem::find()->where(['lddapada_id' => $lddapada_id, 'creditor_id' => $model->creditor_id, 'active'=>1])->count();
                                         return ['checked' => $bool,
                                                'onclick'=>'onCreditor(this.value,this.checked)' //additional
                                                ];
                                     }
            ],
            [
                'attribute' => 'name',
                'value'=>function ($model, $key, $index, $widget){ 
                            return $model->request->creditor->name;
                        },
            ],
            [
                'attribute' => 'account_number',
                'value'=>function ($model, $key, $index, $widget){ 
                            return $model->request->creditor->account_number;
                        },
            ],
            //'price_catalogue',
        ],
    ]); 
?>
<script type="text/javascript">
function onCreditor(creditor_id,checked){
    var lddapada_id = <?php echo $lddapada_id?>;
    $.ajax({
            type: "POST",
            url: "<?php echo Url::to(['lddapada/additems']); ?>",
            data: {creditorId:creditor_id,lddapadaId:lddapada_id,checked:checked},
            success: function(data){ 
                }
            });

    return false;
  //});
}    
</script>


