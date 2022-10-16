<?php
use kartik\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

use kartik\datecontrol\DateControl;
use kartik\detail\DetailView;
use kartik\editable\Editable; 
use kartik\grid\GridView;
?>
<?php Pjax::begin(); ?>
      <?php
        echo GridView::widget([
            'id' => 'obligation',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                            [
                                'class' => '\kartik\grid\CheckboxColumn',
                                'headerOptions' => ['class' => 'kartik-sheet-style'],
                                'name'=>'obligation-request-id', //additional
                                'checkboxOptions' => function($model, $key, $index, $column){
                                                         //$bool = Ppmpitem::find()->where(['ppmp_id' => $id, 'item_id' => $model->item_id, 'active'=>1])->count();
                                                         return [//'checked' => $bool,
                                                                    //'onclick'=>'onObligationSelect(this.value,this.checked)' //additional
                                                                    'onclick'=>'
                                                                        if (confirm("Do you want to process the selected request?")) {
                                                                                /*onObligationSelect(this.value)*/
                                                                                window.location.replace("/budget/obligation/processrequest?id="+this.value);
                                                                            } else {
                                                                                $(this).prop("checked", false);
                                                                            }
                                                                    ' //additional
                                                                ];
                                                     }
                            ],
                            [
                                'attribute'=>'payee',
                                'width'=>'300px',
                                'contentOptions' => [
                                    'style'=>'max-width:200px; overflow: auto; white-space: normal; word-wrap: break-word;'
                                ],
                                'format' => 'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return '<b>' . $model->payee . '</b><br/><p style="font-size: x-small;">' . $model->particulars . '</p>';
                                      },
                            ],
                            [
                                'attribute'=>'os_no',
                                'headerOptions' => ['style' => 'text-align: center; background-color: #7e9fda;'],
                                'contentOptions' => ['style' => 'text-align: center; font-weigth: bold;'],
                                'width'=>'100px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->os_no;
                                },
                            ],
                            [
                                'attribute'=>'os_date',
                                'headerOptions' => ['style' => 'text-align: center; background-color: #7e9fda;'],
                                'contentOptions' => ['style' => 'text-align: center; font-weigth: bold;'],
                                'width'=>'100px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->os_date;
                                },
                            ],
                            [
                                'attribute'=>'amount',
                                
                                'contentOptions' => ['style' => 'text-align: right;'],
                                'width'=>'75px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    $fmt = Yii::$app->formatter;
                                    return $fmt->asDecimal($model->amount);
                                },
                            ],
                            
                    ],
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            'toggleDataOptions' => ['minCount' => 10],
            //'exportConfig' => $exportConfig,
            'itemLabelSingle' => 'item',
            'itemLabelPlural' => 'items'
        ]);
        ?>
<?php Pjax::end(); ?>