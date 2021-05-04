<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\procurement\Purchaserequest */

$this->title = $detail->purchase_request_id;
$this->params['breadcrumbs'][] = ['label' => 'Purchaserequests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchaserequest-view">

    <?= DetailView::widget([
        'model' => $detail,
        'attributes' => [
            //'purchase_request_id',
            'purchase_request_number',
            //'purchase_request_sai_number',
            //'division_id',
            //'section_id',
            'purchase_request_date',
            //'purchase_request_saidate',
            //'purchase_request_purpose:ntext',
            //'purchase_request_referrence_no',
            //'purchase_request_project_name',
            //'purchase_request_location_project',
            //'purchase_request_requestedby_id',
            //'purchase_request_approvedby_id',
            //'user_id',
        ],
    ]) ?>

    <?= GridView::widget([
    'dataProvider'=> $dataProvider,
    //'filterModel' => $searchModel,
    'summary' => false,
    'columns' => [
        ['attribute' => 'unit_id'],
        
        ['attribute' => 'purchase_request_details_item_description'],
        ['attribute' => 'purchase_request_details_quantity'],
        ['attribute' => 'purchase_request_details_price'],
        [
            'attribute' => 'purchase_request_details_price',
            'header' => 'Total',
            'value' => function($model){
                
            }
        ],
    ],
    //'floatHeader'=>true,
    //'floatHeaderOptions'=>['top'=>'50']
]);?>
</div>