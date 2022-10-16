<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\web\View;
use yii2mod\alert\Alert;
use common\components\Functions;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PurchaserequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Purchase Request';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('/js/purchaserequest/ajax-modal-popup.js');
$this->registerCss($this->render('pr-modal-additems.css'));

$func = new Functions();
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
                    <div class="purchaserequest-search">
                        <?php
                        echo $this->render('_search', ['model' => $searchModel]);
                        //echo Html::input('text', 'PurchaserequestSearch[purchase_request_number]', '', ['id' => 'purchaserequestsearch-purchase_request_number', 'class' => 'form-control']);
                        ?>
                    </div>
                </div>
                <div class="col-md-2">
                    <?php // Html::a('Create Purchase Request', ['create'], ['class' => 'btn btn-success btn-block']) 
                    ?>
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
                //'pjax' => true,
                /*'pjaxSettings' => [
                    'neverTimeout'=>true,
                    'options' => [
                        'enablePushState' => true,
                        'id' => 'pr-pjax'
                    ],
                ],*/
                //'filterModel' => $searchModel,
                'summary' => false,
                'tableOptions' => ['id' => 'pr-table'],
                'options' => ['style' => 'table-layout:fixed;'],
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'purchase_request_number',
                        //'enableSorting' => true,
                        'label' => 'PR Number',
                        'headerOptions' => [
                            'style' => 'width:11%'
                        ],
                    ],
                    [
                        'attribute' => 'purchase_request_purpose',
                        'header' => 'Purpose',
                        'format' => 'ntext',
                        'headerOptions' => [
                            'style' => 'width:40%'
                        ],
                        'value' => 'purchase_request_purpose'
                    ],
                    [
                        'attribute' => 'division_name',
                        'header' => 'Division',
                        'value' => function ($model, $key, $index, $widget) {
                            if ($model) {
                                return $model->division_name;
                            }
                        }
                    ],
                    [
                        'attribute' => 'purchase_request_requestedby_id',
                        'header' => 'Requested by',
                        'value' => function ($model, $key, $index, $widget) {
                            //$user = $model->requestedby->profile;
                            if ($model) {
                                return $model->requested_by;
                            }
                        }

                    ],

                    [
                        'attribute' => 'purchase_request_id',
                        'format' => 'html',
                        'header' => 'P.O. Numbers',
                        'value' => function ($model, $key, $index, $widget) {
                            return $model->po_number;
                        }
                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'options' => ['style' => 'width:12%'],
                        'template' => '{view} {update} {print}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['id' => 'buttonViewPR', 'value' => Url::to(['view', 'id' => $model->purchase_request_id]), 'class' => 'btn btn-success btn-sm', 'data-toggle' => 'tooltip', 'title' => 'view']);
                                //return Html::a('<button type="button" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-eye-open"></span></button>',$url);  
                                //return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['href' => Url::to($url),'class' => 'btn btn-success btn-sm']); 
                            },
                            'update' => function ($url, $model) {
                                return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['id' => 'buttonUpdatePR', 'value' => Url::to(['update', 'id' => $model->purchase_request_id]), 'class' => 'btn btn-info btn-sm', 'data-toggle' => 'tooltip', 'title' => 'update']);
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

Modal::begin([
    'header' => '<h4 id="modalHeader2" style="color: #ffffff"></h4>',
    'id' => 'viewPRModal',
    //'size' => 'modal-lg',
    'options' => [
        'tabindex' => false,
    ],
]);
echo "<div id='modalContent2'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

// This section will allow to popup a notification
$session = Yii::$app->session;
if ($session->isActive) {
    $session->open();
    if (isset($session['deletepopup'])) {
        $func->CrudAlert2("Deleted Successfully", Alert::TYPE_WARNING);
        unset($session['deletepopup']);
        $session->close();
    }
    if (isset($session['updatepopup'])) {
        $func->CrudAlert2("Updated Successfully");
        unset($session['updatepopup']);
        $session->close();
    }
    if (isset($session['savepopup'])) {
        $func->CrudAlert2("Saved Successfully", Alert::TYPE_SUCCESS, true);
        unset($session['savepopup']);
        $session->close();
    }
    if (isset($session['errorpopup'])) {
        $func->CrudAlert2("Error Transaction", Alert::TYPE_WARNING, true);
        unset($session['errorpopup']);
        $session->close();
    }
}
?>



<script>
    $(document).ready(function() {
        $('div.sa-confirm-button-container button.confirm').click(function() {
            location.reload();
        });


        /************Filter Table************/
        /*
        $("#purchaserequestsearch-purchase_request_number").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("table#pr-table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });*/
        
    });
</script>