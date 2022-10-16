<?php 

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use common\models\procurement\Expenditureclass;
use common\models\procurement\Expenditureobject;
?>
<div class="budgetallocation-view" style="width: 80%; margin-left: 80px;">   
<?php 
echo GridView::widget([
                'dataProvider'=> $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'budget_allocation_id',
                        'width' => '30%',
                        'value'=>function ($model, $key, $index, $widget){ 
                                    return $model->budgetallocation->section->name;
                                },
                        'headerOptions' => ['style' => 'text-align: center'],
                        'contentOptions' => ['style' => 'text-align: left'],
                    ],
                    [
                        'attribute' => 'amount',
                        'width' => '15%',
                        'value'=>function ($model, $key, $index, $widget){ 
                                    $fmt = Yii::$app->formatter;
                                    return $fmt->asDecimal($model->amount);
                                },
                        'headerOptions' => ['style' => 'text-align: center'],
                        'contentOptions' => ['style' => 'text-align: right'],
                    ],
                    [
                        'attribute' => 'amount',
                        'header'=>'% from NEP',
                        'width' => '10%',
                        'value'=>function ($model, $key, $index, $widget){ 
                                    $fmt = Yii::$app->formatter;
                                    return $fmt->asPercent($model->amount / $model->expenditure->amount);
                                },
                        'headerOptions' => ['style' => 'text-align: center'],
                        'contentOptions' => ['style' => 'text-align: center'],
                    ],
                    [
                        'attribute' => 'amount',
                        'header'=>'Actual Expenditure',
                        'width' => '15%',
                        'value'=>function ($model, $key, $index, $widget){ 
                                    $fmt = Yii::$app->formatter;
                                    return $fmt->asDecimal(0);
                                },
                        'headerOptions' => ['style' => 'text-align: center'],
                        'contentOptions' => ['style' => 'text-align: center'],
                    ],
                    [
                        'attribute' => 'amount',
                        'header'=>'Fund Available',
                        'width' => '15%',
                        'value'=>function ($model, $key, $index, $widget){ 
                                    $fmt = Yii::$app->formatter;
                                    return $fmt->asDecimal(0);
                                },
                        'headerOptions' => ['style' => 'text-align: center'],
                        'contentOptions' => ['style' => 'text-align: center'],
                    ],
                ],
                'responsive'=>true,
                'hover'=>true
            ]); ?>
</div>