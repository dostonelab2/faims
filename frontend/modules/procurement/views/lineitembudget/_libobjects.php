<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use common\models\procurement\Lineitembudgetobject;
?>

<div class="table-scroll" style="height: 750px!important;">

<?php
echo GridView::widget([
                'dataProvider'=> $dataProvider,
                'id' => 'expenditure-objects',
                'export'=>false,
                'columns' => [
                                [
                                    'class' => 'kartik\grid\CheckboxColumn',
                                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                                    'checkboxOptions' => function($model, $key, $index, $column){
                                         $bool = Lineitembudgetobject::find()->where(['line_item_budget_id' => $_GET['id'], 'expenditure_object_id' => $model->expenditure_object_id])->count();
                                         return ['checked' => $bool];
                                     }
                                ],
                                [
                                    'attribute'=>'expenditureSubClass.expenditureClass.expenditure_class_id', 
                                    'width'=>'250px',
                                    'value'=>function ($model, $key, $index, $widget) { 
                                        return $model->expenditureSubClass->expenditureClass->name;
                                    },
                                    'group'=>true,  // enable grouping,
                                    'groupedRow'=>true,                    // move grouped column to a single grouped row
                                    'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                                    'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                                ],
                                [
                                    'attribute'=>'expenditure_sub_class_id', 
                                    'width'=>'250px',
                                    'value'=>function ($model, $key, $index, $widget) { 
                                        return $model->expenditureSubClass->name;
                                    },
                                    'group'=>true,  // enable grouping,
                                    'groupedRow'=>true,                    // move grouped column to a single grouped row
                                    //'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                                    //'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                                ],
                                'name', 
                                'object_code',
                                //'account_code'
                            ],
                'responsive'=>true,
                'hover'=>true
            ]); 
?>
</div>
<script>
$(function(){
    $('.kv-row-checkbox').click(function(){
        var chkRow = $(this);
        var libId = <?php echo $_GET['id']?>;
        var checked = chkRow.is(':checked');
        $.ajax({
                type: "POST",
                url: "<?php echo Url::to(['lineitembudget/addexpenditure']); ?>",
                data: { expenditureObjectId : chkRow.val(), lineItemBudgetId : libId, checked: checked },
                success: function(){ 
                    if(chkRow.is(':checked'))
                        chkRow.prop("checked", false);
                    else
                        chkRow.prop("checked", true);
                    },
                });

        return false;
    });
});
</script>