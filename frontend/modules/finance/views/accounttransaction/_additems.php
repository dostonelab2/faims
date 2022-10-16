<?php
use kartik\grid\GridView;
use kartik\select2\Select2;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ActiveForm;

use common\models\finance\Accounttransaction;
//use common\models\procurement\Expenditure;
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
                'checkboxOptions' => function($model, $key, $index, $column) use ($id, $year){
                                         $bool = Accounttransaction::find()->where(['request_id'=>$id, 'account_id' => $model->account_id, 'active'=>1])->count();
                                         return ['checked' => $bool,
                                                'onclick'=>'onAdditem('.$id.',this.value,this.checked)' //additional
                                                ];
                                     }
            ],
            [
                'attribute' => 'title',
                'value'=>function ($model, $key, $index, $widget) { 
                            return $model->title;
                        },
            
                //'group'=>true,  // enable grouping,
                //'groupedRow'=>true,                    // move grouped column to a single grouped row
                //'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                //'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
            ],
            [
                'attribute' => 'object_code',
                'value'=>function ($model, $key, $index, $widget) { 
                            return $model->object_code;
                        },
            
                //'group'=>true,  // enable grouping,
                //'groupedRow'=>true,                    // move grouped column to a single grouped row
                //'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                //'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
            ],
            /*[
                'attribute' => 'name',
                'value'=>function ($model, $key, $index, $widget){ 
                            return $model->name;
                        },
            ],*/
    ]]); 
?>
<script type="text/javascript">
function onAdditem(id,accountid,checked){
    $.ajax({
            type: "POST",
            url: "<?php echo Url::to(['accounttransaction/additem']); ?>",
            data: {id: id, accountid: accountid, checked: checked},
            success: function(data){ 
                }
            });

    return false;
  //});
}    
</script>


