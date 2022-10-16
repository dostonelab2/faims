<?php 

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use common\models\procurement\Expenditureclass;
use common\models\procurement\Expenditureobject;
                      
echo GridView::widget([
                'dataProvider'=> $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class' => 'kartik\grid\CheckboxColumn',
                        'headerOptions' => ['class' => 'kartik-sheet-style'],
                    ],
                    //'expenditure_sub_class_id', 
                    'name', 
                ],
                'responsive'=>true,
                'hover'=>true
            ]); ?>