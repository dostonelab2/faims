<?php
use kartik\grid\GridView;
use kartik\select2\Select2;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ActiveForm;

use common\models\budget\Budgetallocationitem;
use common\models\procurement\Expenditure;
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

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
            'heading'=>'<h3 class="panel-title">Select Expenditure</h3>',
            'type'=>'success',

         ],
        'showPageSummary' => true,
        'toolbar'=> [],
        'columns' => [
            [
                'class' => 'kartik\grid\RadioColumn',
                'width' => '36px',
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'radioOptions' => function($model, $key, $index, $column) use ($id){
                        $item = Budgetallocationitem::find()->where(['category_id'=>$key, 'budget_allocation_id'=>$id, 'expenditure_class_id'=>$model->expenditureSubClass->expenditureClass->expenditure_class_id, 'expenditure_subclass_id'=>$model->expenditure_sub_class_id])
                        ->one();
                            if($item){
                                return [
                                    'onclick'=>'{
                                        $("#allocationadjustment-item_id").val("'.$item->budget_allocation_item_id.'");
                                        $("#allocationadjustment-new_item").val(0);
                                        }' //additional
                                ];
                            }else{
                                return [
                                    'onclick'=>'{
                                        $("#allocationadjustment-item_id").val("'.$model->expenditure_object_id.'");
                                        $("#allocationadjustment-new_item").val("'.$key.'");
                                    }' //additional
                                    
                                ];
                            }                             
                         }
            ],
            [
                'attribute' => 'expenditureClass',
                'value'=>function ($model, $key, $index, $widget) { 
                            return $model->expenditureSubClass->expenditureClass->name;
                        },
            
                'group'=>true,  // enable grouping,
                'groupedRow'=>true,                    // move grouped column to a single grouped row
                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
            ],
            [
                'attribute' => 'ExpenditureSubClass',
                'value'=>function ($model, $key, $index, $widget) { 
                            return $model->expenditureSubClass->name;
                        },
            
                'group'=>true,  // enable grouping,
                'groupedRow'=>true,                    // move grouped column to a single grouped row
                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
            ],
            [
                'attribute' => 'name',
                'value'=>function ($model, $key, $index, $widget){ 
                            return $model->name;
                        },
            ],
            [
                'attribute' => 'amount',
                'header' => 'Amount Allocated',
                //'value'=>function ($model, $key, $index, $widget) use ($id, $budgetAllocationId, $budgetAllocationItemId, $sourceId){ 
                'value'=>function ($model, $key, $index, $widget) use ($id){ 
                            $item = Budgetallocationitem::find()->where(['category_id'=>$key, 'budget_allocation_id'=>$id, 'expenditure_class_id'=>$model->expenditureSubClass->expenditureClass->expenditure_class_id, 'expenditure_subclass_id'=>$model->expenditure_sub_class_id])
//                                ->with(['budgetallocation' => function($query){
//                                $query->andWhere(['budget_allocation_id' => $budgetAllocationId]);
//                            }])->one();
                                
                                ->one();
                            if($item)
                                return $item->amount;
                                //return $items->amount.' - '.$items->budget_allocation_item_id.' - '.$items->name.' - '.$key;
                            else
                                return '-';
                        },
            ],
    ]]); 
?>
<script type="text/javascript">
function onAdditem(id,objectid,year,checked){
    $.ajax({
            type: "POST",
            url: "<?php echo Url::to(['budgetallocationitem/additem']); ?>",
            data: {id:id,objectid:objectid,year:year,checked:checked},
            success: function(data){ 
                }
            });

    return false;
  //});
}    
</script>


