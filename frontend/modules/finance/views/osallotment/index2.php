<?php
use kartik\grid\GridView;
use kartik\select2\Select2;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ActiveForm;

use common\models\finance\Osallotment;
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
                'format'=>'raw',
                'value'=>function ($model, $key, $index, $widget) { 
                            return '&nbsp;&nbsp;&nbsp;&nbsp;'.$model->expenditureSubClass->name;
                        },
                
                'group'=>true,  // enable grouping,
                'groupedRow'=>true,                    // move grouped column to a single grouped row
                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
            ],
            [
                'attribute' => 'name',
                'format'=>'raw',
                'value'=>function ($model, $key, $index, $widget){ 
                            return '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$model->name;
                        },
            ],
    ]]); 
?>