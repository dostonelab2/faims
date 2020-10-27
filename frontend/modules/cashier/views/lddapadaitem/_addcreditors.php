<?php
use kartik\grid\GridView;
use kartik\select2\Select2;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use yii\helpers\Url;

use yii\widgets\ActiveForm;

use common\models\cashier\Lddapadaitem;
use common\models\finance\Request;
/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\Ppmpitem */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
        $gridColumns = [
                [
                    'class' => '\kartik\grid\CheckboxColumn',
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'name'=>'lddap-ada-items', //additional
                    'checkboxOptions' => function($model, $key, $index, $column) use ($id){
                                             //$bool = Lddapadaitem::find()->where(['lddapada_id' => $id, 'creditor_id' => $model->request->payee_id, 'active'=>1])->count();
                                             $bool = Lddapadaitem::find()->where(['osdv_id' => $model->osdv_id, 'active'=>1])->count();
                                             return ['checked' => $bool,
                                                    'onclick'=>'onCreditor(this.value,this.checked)' //additional
                                                    ];
                                         }
                ],
                [
                    'attribute' => 'request_id',
                    'label' => 'Name',
                    'value'=>function ($model, $key, $index, $widget){ 
                                return $model->request->creditor->name;
                            },
                ],
                [   
                    'attribute' => 'request_id',
                    'label' => 'Fund Source',
                    'value'=>function ($model, $key, $index, $widget){ 
                                return $model->type->name;
                            },
                ],
                [
                    'attribute' => 'request_id',
                    'label' => 'DV Number',
                    'value'=>function ($model, $key, $index, $widget){ 
                                return $model->dv->dv_number;
                            },
                ],
                [
                    'attribute' => 'amount',
                    'label' => 'Amount',
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: right; padding-right: 25px; vertical-align: middle;'],
                    'format' => ['decimal',2],
                    'value'=>function ($model, $key, $index, $widget){ 
                                return $model->request->amount;
                            },
                ],
            ];
    ?>
    <?= GridView::widget([
                'id' => 'lddap-ada-items',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => true, // pjax is set to always true for this demo
                // set left panel buttons
                'panel' => [
                    'heading'=>'<h3 class="panel-title">CREDITORS</h3>',
                    'type'=>'primary',
                ],
                // set right toolbar buttons
                'toolbar' => 
                                [
                                    [
                                        'content'=> '',
                                    ],
                                ],
                // set export properties
                'export' => [
                    'fontAwesome' => true
                ],
                'persistResize' => false,
                'toggleDataOptions' => ['minCount' => 10],
                //'exportConfig' => $exportConfig,
                'itemLabelSingle' => 'item',
                'itemLabelPlural' => 'items'
            ]);
    
    ?>


<script type="text/javascript">
function onItem(creditor_id,checked){
    var lddapada_id = <?php echo $id?>;
    $.ajax({
            type: "POST",
            url: "<?php echo Url::to(['lddapada/additems']); ?>",
            //data: {creditorId:creditor_id,lddapadaId:lddapada_id,checked:checked},
            data: {creditorId:creditor_id,lddapadaId:lddapada_id,checked:checked},
            success: function(data){ 
                }
            });

    return false;
  //});
}
    
function onCreditor(osdv_id,checked){
    var lddapada_id = <?php echo $id?>;
    $.ajax({
            type: "POST",
            url: "<?php echo Url::to(['lddapada/additems']); ?>",
            //data: {creditorId:creditor_id,lddapadaId:lddapada_id,checked:checked},
            data: {osdvId:osdv_id,lddapadaId:lddapada_id,checked:checked},
            success: function(data){ 
                }
            });

    return false;
  //});
}        
</script>


