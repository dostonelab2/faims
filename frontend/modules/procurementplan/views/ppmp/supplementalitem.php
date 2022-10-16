<?php

use yii\helpers\Html;
use yii\helpers\Url;

use kartik\detail\DetailView;
use kartik\editable\Editable;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;

use common\models\procurementplan\Ppmp;
use common\models\procurement\Project;

/* @var $this yii\web\View */
/* @var $model common\models\procurementplan\Ppmpitem */
/* @var $form ActiveForm */

$this->title = 'Supplemental Items';
$this->params['breadcrumbs'][] = ['label' => $model->unit->name, 'url' => ['view', 'id' => $model->ppmp_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplementalitem">
<?php

$gridColumns = $gridColumns = [
                [
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '20px',
                    'header' => '',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    //'mergeHeader' => true,
                ],
                [
                    'attribute'=>'month',
                    'header'=>'Month',
                    'width'=>'100px',
                    'value'=>function ($model, $key, $index, $widget) { 
                            if($model->month == 1){
                                return 'January';
                            }elseif ($model->month == 2) {
                                return 'February';
                            }elseif ($model->month == 3) {
                                return 'March';
                            }elseif ($model->month == 4) {
                                return 'April';
                            }elseif ($model->month == 5) {
                                return 'May';
                            }elseif ($model->month == 6) {
                                return 'June';
                            }elseif ($model->month == 7) {
                                return 'July';
                            }elseif ($model->month == 8) {
                                return 'August';
                            }elseif ($model->month == 9) {
                                return 'September';
                            }elseif ($model->month == 10) {
                                return 'October';
                            }elseif ($model->month == 11) {
                                return 'November';
                            }elseif ($model->month == 12) {
                                return 'December';
                            }
                        },
                    'headerOptions' => ['style' => 'text-align: left; background-color: #7e9fda;'],
                    'contentOptions' => ['style' => 'text-align: left; background-color: #7e9fda; font-weight: bold;'],
                
                    'group'=>true,  // enable grouping,
                    'groupedRow'=>true,                    // move grouped column to a single grouped row
                    'groupOddCssClass'=>'',  // configure odd group cell css class
                    'groupEvenCssClass'=>'', // configure even group cell css class
                ],
                [
                    'attribute'=>'description', 
                    'header'=>'General Description',
                    'width'=>'650px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: left'],
                    'mergeHeader' => true,
                ],
                [
                    'attribute'=>'quantity', 
                    'header'=>'Quantity',
                    'width'=>'100px',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'mergeHeader' => true,
                ],
    
            ];


echo GridView::widget([
        'dataProvider'=> $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'pjax'=>true,
        //'responsive'=>false,
        //'hover'=>true,
        'panel' => [
                    'heading'=>'<h3 class="panel-title">Supplemental Items</h3>',
                    'type'=>'primary',
                    /*'before'=>Html::button('Add Items', ['value' => Url::to(['ppmpitem/additems', 'id'=>$model->ppmp_id, 'year'=>$model->year]), 'title' => 'PPMP Item', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonAddPpmpItem']),
                    'after'=>false,*/
                    'before'=> Html::a('Add Item', ['ppmp/supplementalitem'], [
                                                                    'class' => 'btn btn-primary',
                                                                    'style' => 'margin-right: 6px; display: "";',
                                                                    'data' => [
                                                                        'confirm' => 'Are you sure you want to add supplemental item',
                                                                        'method' => 'post',],])
                ],
        'toolbar' => 
                                [
                                    [
                                        'content'=> Html::button('PENDING', ['title' => 'Add Item', 'class' => 'btn btn-primary', 'style'=>'width: 90px; margin-right: 6px;']) 
                                    ],
                                ],
                // set export properties
        'export' => [
                    'fontAwesome' => true
                ],
        'persistResize' => false,
        'toggleDataOptions' => ['minCount' => 10],
                //'exportConfig' => $exportConfig,
        'itemLabelSingle' => 'item',
        'itemLabelPlural' => 'items'
]);
?>
</div><!-- supplementalitem -->
