<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\View;
use common\models\procurementplan\Ppmpitem;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\procurementplan\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_itemsearch', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'pjaxSettings' => [
                    'options' => [
                            'enablePushState' => false,
                            'id' => 'additemgrid',
                            'timeout' => 1000, 
                            'clientOptions' => ['backdrop' => false]
                        ],
                    ],
        'columns' => [
            [
                'class' => '\kartik\grid\CheckboxColumn',
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'name'=>'ppmp-item', //additional
                'checkboxOptions' => function($model, $key, $index, $column){
                                         $bool = Ppmpitem::find()->where([
                                            'ppmp_id' => $_GET['id'],
                                            'item_id' => $model->item_id,
                                            'supplemental' => 1,
                                            'status_id' => 2,
                                            'active'=> 1,
                                            //'month' => Yii::$app->request->get('month'), ])->count();
                                            'month' => $_GET['selectMonth'], ])->count();
                                         return ['checked' => $bool,
                                                'class' => 'checkbox',
                                                //'disabled' => true,
                                                'onclick'=>'onPPMP(this.value,this.checked)' //look for javasript bellow...
                                                ];
                                     }
            ],

            //'item_id',
            'item_category_id',
            //'item_code',
            'item_name',
            'unit_of_measure_id',
            'price_catalogue',
            // 'last_update',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
<script type="text/javascript">
  //document.getElementById('dropdown').value = "<?php //echo $_GET['selectMonth'];?>";
  $(document).ready(function() {
    $("body").on("change","#dropdown",function () {
        /*
        var month = $("#dropdown").val();
                $.ajax({
                        type: "GET",
                        url: "<?php //echo Url::to(['supplemental/additems', 'id' => $_GET['id']]); ?>",
                        data: {month:month},
                        //contentType: "application/json; charset=utf-8",
                        dataType: "json"
        
            });*/
          $.pjax.reload({url: "<?php echo Url::to(['supplemental/additems', 'id' => $_GET['id'],'selectMonth' => $_GET['selectMonth']]); ?>", method: 'GET', container:'#additemgrid'});
        
        });

    $("#modal").on("hidden.bs.modal", function () {
        
        location.replace("<?php echo Url::to(['supplemental/index', 'id' => $_GET['id']]); ?>")
    });
});
</script>