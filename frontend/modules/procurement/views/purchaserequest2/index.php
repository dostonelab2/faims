<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PurchaserequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Purchase Request';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('/js/purchaserequest/ajax-modal-popup.js');
?>

<div class="purchaserequest-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h2><i class="fa fa-cart-plus"></i><?= Html::encode(' PURCHASE REQUEST') ?></h2>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <?= $this->render('_search', ['model' => $searchModel]) ?>
                </div>
                <div class="col-md-2">
                    <?php // Html::a('Create Purchase Request', ['create'], ['class' => 'btn btn-success btn-block']) ?>
                    <?=
                    Html::button('Create Purchase Request  <i class="glyphicon glyphicon-list"></i>', [
                        //'disabled' => $model->status_id != 1 or !$isMember,
                        'value' => Url::to(['create']),
                        'title' => 'Create Purchase Request',
                        'class' => 'btn btn-success btn-block',
                        'style' => 'margin-right: 6px; display: "";',
                        'id' => 'buttonCreatePR'
                    ])
                    ?>
                </div>
            </div>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'summary' => false,
                'options' => ['style' => 'table-layout:fixed;'],
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
                    'purchase_request_number',
                    [
                        'attribute' => 'purchase_request_purpose',
                        'format' => 'ntext',
                        'headerOptions' => [
                            'style' => 'width:40%'
                        ],
                        'value' => 'purchase_request_purpose'
                    ],
                    [
                        'attribute' => 'division_id',
                        'value' => function ($model, $key, $index, $widget) {
                            return $model->division->name;
                        }
                    ],
                    [
                        'attribute' => 'purchase_request_requestedby_id',
                        'value' => function ($model, $key, $index, $widget) {
                            //$user = $model->requestedby->profile;
                            if ($model->profile) {
                                return $model->profile->fullname;
                            }
                        }

                    ],

                    [
                        'attribute' => 'purchase_request_id',
                        'format' => 'html',
                        'header' => 'P.O. Numbers',
                        'value' => function ($model, $key, $index, $widget) {
                            return $model->getPonumber();
                        }
                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'options' => ['style' => 'width:12%'],
                        'template' => '{view} {update} {print}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model->purchase_request_id], ['class' => 'btn btn-success btn-sm', 'data-toggle' => 'tooltip', 'title' => 'view']);
                                //return Html::a('<button type="button" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-eye-open"></span></button>',$url);  
                                //return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['href' => Url::to($url),'class' => 'btn btn-success btn-sm']); 
                            },
                            'update' => function ($url, $model) {

                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->purchase_request_id], ['class' => 'btn btn-info btn-sm', 'data-toggle' => 'tooltip', 'title' => 'update']);
                            },
                            'print' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-print"></span>', ['/procurement/purchaserequest/reportprfull', 'id' => $model->purchase_request_id], ['class' => 'btn btn-warning btn-sm', 'data-toggle' => 'tooltip', 'title' => 'print', 'target' => '_blank']);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>


<?php
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'createPRModal',
    //'size' => 'modal-lg',
    'options' => [
        'tabindex' => false,
    ],
]);
echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();
?>

