<?php
use kartik\grid\GridView;
use kartik\select2\Select2;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

use yii\widgets\ActiveForm;

use common\models\budget\Budgetallocationitemdetails;
//use common\models\procurement\Expenditure;
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
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
                                         $bool = Budgetallocationitemdetails::find()->where(['budget_allocation_item_id'=>$id, 'program_id' => $model->program_id, 'active'=>1])->count();
                                         return ['checked' => $bool,
                                                 /*  id => budget_allocation_item_id,
                                                     itemId => program_id
                                                     year => year
                                                     checked => checked
                                                 */
                                                'onclick'=>'onAddprogram('.$id.',this.value,'.$year.',this.checked)' //additional
                                                ];
                                     }
            ],
            /*[
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
            ],*/
            [
                'attribute' => 'name',
                'value'=>function ($model, $key, $index, $widget){ 
                            return $model->name;
                        },
            ],
    ]]); 
?>
<script type="text/javascript">
/*  id => budget_allocation_item_id,
    itemId => program_id
    year => year
    checked => checked
*/
function onAddprogram(id,itemId,year,checked){
    $.ajax({
            type: "POST",
            url: "<?php echo Url::to(['budgetallocationitemdetails/addprogram']); ?>",
            data: {id:id,itemId:itemId,year:year,checked:checked},
            success: function(data){ 
                }
            });

    return false;
  //});
}    
</script>


