<?php
use kartik\grid\GridView;
use kartik\select2\Select2;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ActiveForm;

use common\models\procurementplan\Itemcategory;
use common\models\procurementplan\Ppmpitem;
use common\models\procurementplan\Unitofmeasure;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\Ppmpitem */
/* @var $form yii\widgets\ActiveForm */
?>



   <?php $form = ActiveForm::begin(); ?>

    <?=$form->field($searchModel, 'item_id')->dropDownList([
        '1' => 'January',
        '2' => 'February',
        '3' => 'March',
        '4' => 'April',
        '5' => 'May',
        '6' => 'June',
        '7' => 'July',
        '8' => 'August',
        '9' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December'
    ],
    [
        'prompt' => 'Select Month...',
        //'onchange' => 'selectMonth(this.value)',
        'id' => 'dropdown'
    ]
    )->label(false);?>
    

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id'=>'ppmp_items', //additional
        'pjax' => true, // pjax is set to always true for this demo
                'pjaxSettings' => [
                        'options' => [
                            'enablePushState' => false,
                            'id' => 'pjax-grid-view',
                        ],
                    ],
        'columns' => [
            [
                'class' => '\kartik\grid\CheckboxColumn',
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'name'=>'ppmp-item', //additional
                'checkboxOptions' => function($model, $key, $index, $column) use ($id,$month){
                                         $bool = Ppmpitem::find()->where([
                                            'ppmp_id' => $id,
                                            'item_id' => $model->item_id,
                                            'supplemental' => 1,
                                            'status_id' => 0,
                                            'active'=> 1,
                                            //'month' => Yii::$app->request->get('month'), ])->count();
                                            'month' => $month, ])->count();
                                         return ['checked' => $bool,
                                         		'class' => 'checkbox',
                                         		//'disabled' => true,
                                                'onclick'=>'onPPMP(this.value,this.checked)' //look for javasript bellow...
                                                ];
                                     }
            ],
            [
                    'attribute'=>'availability',
                    'header'=>'Category',
                    'value'=>function ($model, $key, $index, $widget) { 
                            if($model->availability == 1){
                                return 'PART I. AVAILABLE AT PROCUREMENT SERVICE STORES';
                            }elseif($model->availability == 2){
                                return 'PART II. OTHER ITEMS NOT AVALABLE AT PS BUT REGULARLY PURCHASED FROM OTHER SOURCES (Note: Please indicate price of items)';
                            }
                        },
                    'headerOptions' => ['style' => 'background-color: #fee082;'],
                    'contentOptions'=>['style'=>'background-color: #fee082;'],
                
                    'group'=>true,  // enable grouping,
                    'groupedRow'=>true,                    // move grouped column to a single grouped row
                    //'contentOptions' => ['style' => 'text-align: left; background-color: #ffe699;'],
                    
                    'groupOddCssClass'=>'',  // configure odd group cell css class
                    'groupEvenCssClass'=>'', // configure even group cell css class
                ],
            [
                'attribute' => 'item_category_id',
                'value'=>function ($model, $key, $index, $widget) { 
                            return $model->itemcategory->category_name;
                        },
                'filter' => ArrayHelper::map(Itemcategory::find()->asArray()->all(), 'item_category_id', 'category_name'),
                'filterType' => GridView::FILTER_SELECT2,
                /*'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Select'],*/
                'headerOptions' => ['style' => 'text-align: left; background-color: #7e9fda;'],
                'contentOptions' => ['style' => 'text-align: left; background-color: #7e9fda;'],
                
                'group'=>true,  // enable grouping,
                'groupedRow'=>true,                    // move grouped column to a single grouped row
                'groupOddCssClass'=>'',  // configure odd group cell css class
                'groupEvenCssClass'=>'', // configure even group cell css class
            ],
            [
                'attribute' => 'item_name',
                'contentOptions' => ['style' => 'width: 1100px; white-space: normal;'],
                //'width'=>'100px',
                'value'=>function ($model, $key, $index, $widget){ 
                            return $model->item_name.' ('.$model->item_code.')';
                        },
            ],
            [
                'attribute' => 'unit_of_measure_id',
                'header' => 'UoM',
                'value'=>function ($model, $key, $index, $widget){ 
                            return $model->getUnittype();
                        },
            ],
            //'price_catalogue',
            [
                'attribute' => 'price_catalogue',
                'hAlign'=>'right',
                'header' => 'Price',
                'value'=>function ($model, $key, $index, $widget){ 
                            $fmt = Yii::$app->formatter;
                            //return $model->getUnit();
                            return $fmt->asDecimal($model->price_catalogue);
                        },
            ],
        ],
    ]); 


?>
<?php ActiveForm::end(); ?>



<script type="text/javascript">

function onPPMP(item_id,checked){
    var ppmp_id = <?php echo $id?>;
    var ppmp_year = <?php echo $year?>;
    var month = document.getElementById("dropdown").value;
    var items = document.getElementsByClassName('checkbox');

	if(month == ""){
		krajeeDialog.alert('<i class = "glyphicon glyphicon-calendar" style = "font-size: 20px"></i><b>    Please select month...</b>')
		for (var i = 0; i < items.length; i++) {
            if (items[i].type == 'checkbox')
                items[i].checked = false;
        }
	} else {

	    $.ajax({
	            type: "POST",
	            url: "<?php echo Url::to(['ppmp/addsupplementalitems']); ?>",
	            data: {itemId:item_id,ppmpId:ppmp_id,year:ppmp_year,checked:checked,month:month},
	            success: function(data){ 
	                }
	            });
	    return false;
  //});
  } 
} 

$("#dropdown").change(function(){
    val = $(this).val();
    $.pjax.reload({
                type: "POST",
                url: "<?php echo Url::to(['supplemental/addsupplementalitems', 'id' => $id, 'year' => $year]); ?>",
                container: '#pjax-grid-view',
                data: {month:val}
                
        });
 });


$("#modal").on("hidden.bs.modal", function () {
    // put your default event here
    /*
    $.pjax.reload({
        url: "<?php //echo Url::to(['supplemental/index', 'id' => $id]); ?>",
        container:'#supplementalppmp'
    });*/
    
    location.replace("<?php echo Url::to(['supplemental/index', 'id' => $id]); ?>")
});
</script>


