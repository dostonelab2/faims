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
        'filterModel' => $searchModel,
        'id'=>'expenditure_objects', //additional
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
                'name'=>'expenditure-object', //additional
                'checkboxOptions' => function($model, $key, $index, $column) use ($id, $year){
                                         $bool = Budgetallocationitem::find()->where(['budget_allocation_id'=>$id, 'category_id' => $model->expenditure_object_id, 'active'=>1])->count();
                                         return ['checked' => $bool,
                                                'onclick'=>'onAdditem('.$id.',this.value,'.$year.',this.checked)' //additional
                                                ];
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


