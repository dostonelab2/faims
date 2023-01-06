<?php

use kartik\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

use kartik\datecontrol\DateControl;
use kartik\detail\DetailView;
use kartik\editable\Editable; 
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\OsallotmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Obligations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="osallotment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            '1','2','3','4','7',
            '5010101001',
            '5010201001',
            '5010202000',
            '5010203000',
            '5010204001',
            '5010214001',
            '5010215001',
            '5010000000',
            '5010302001',
            '5010303001',
            '5010304001',
            '5010205002',
            '5010206003',
            '5010211004',
            '5010212003',
            '5010301000',
            '5010499000',
            '5020000000',
            '5020101000',
            '5020102000',
            '5020201000',
            '5020301000',
            '5020302000',
            '5020308000',
            '5020310000',
            '5020311001',
            '5020399000',
            '5020401000',
            '5020402000',
            '5020501000',
            '5020502002',
            '5020502001',
            '5020504000',
            '5021003000',
            '5021101000',
            '5021102000',
            '5021103000',
            '5021202000',
            '5021203000',
            '5021299000',
            '5021304000',
            '5021305000',
            '5021305000',
            '5021306000',
            '5021306000',
            '5021402000',
            '5021403000',
            '5021501000',
            '5021502000',
            '5021503000',
            '5029901000',
            '5029902000',
            '5029903000',
            '5029905001',
            '5029905003',
            '5029905005',
            '5029906000',
            '5029907000',
            '5029999000',
            '5060000000',
            '1080102000',
            
            // 'os_allotment_id',
            // 'osdv_id',
            // 'expenditure_class_id',
            // 'expenditure_object_id',
            // 'amount',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
        'pjax' => true, // pjax is set to always true for this demo
            'panel' => [
//                    'heading' => $this->title,
                    'heading' => '<h2 class="panel-title"><i class="fas fa-folder-open"></i> '.$this->title.'</h2>',
                    'type' => GridView::TYPE_PRIMARY,
                    // 'before'=>  Html::button('<i class="fas fa-plus"></i> Add', ['value' => Url::to(['document/create', 'qms_type_id' => $_GET['qms_type_id']]), 'title' => 'Add Document', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px; '.( ( (Yii::$app->user->identity->username == 'Admin') || Yii::$app->user->can('17025-document-custodian')) ? '' : 'display: none;'), 'id'=>'buttonCreateRequest']),
                    'after'=>false,
                ],

            // set export properties
            'export' => [
                'fontAwesome' => true
            ],
            'exportConfig' => [
                'html' => [],
                'csv' => [],
                'txt' => [],
                'xls' => [],
                'pdf' => [],
                'json' => [],
            ],
    ]); ?>
</div>
