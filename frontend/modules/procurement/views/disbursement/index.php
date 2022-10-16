<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\modules\pdfprint;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\DisbursementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$BaseURL = $GLOBALS['frontend_base_uri'];
$this->registerJsFile($BaseURL.'js/procurement/disbursement/ajax-modal-popup.js');

$this->title = 'Disbursements';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disbursement-index">



    <?php
    //Modal
    Modal::begin([
        'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
        'id' => 'modalDisbursement',
        'size' => 'modal-lg',
        'options'=> [
            'tabindex'=>false,
        ]
    ]);
    echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
    Modal::end();
    ?>



    <?php

    $colorPluginOptions =  [
        'showPalette' => true,
        'showPaletteOnly' => true,
        'showSelectionPalette' => true,
        'showAlpha' => false,
        'allowEmpty' => false,
        'preferredFormat' => 'name',
        'palette' => [
            [
                "white", "black", "grey", "silver", "gold", "brown",
            ],
            [
                "red", "orange", "yellow", "indigo", "maroon", "pink"
            ],
            [
                "blue", "green", "violet", "cyan", "magenta", "purple",
            ],
        ]
    ];

    $gridColumns = [

        [
            'class' => 'kartik\grid\SerialColumn',
            'contentOptions' => ['class' => 'kartik-sheet-style'],
            'width' => '5%',
            'vAlign' => 'top',
            'header' => '',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
        ],

        [
            'attribute'=>'dv_no',
            'label'=>'OS Number',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'width'=>'13%',
        ],

        [

            'attribute'=>'payee',
            'label'=>'Payee',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'width'=>'13%',
        ],

        [

            'attribute'=>'dv_amount',
            'label'=>'Amount',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'contentOptions' => [
                'style'=>'text-align:right;'
            ],
            'width'=>'3%',
        ],

        [
            'attribute'=>'particulars',
            'label'=>'Particulars',
            'vAlign' => 'top',
            'format'=>'raw',
            'contentOptions' => [
                'style'=>'max-width:200px; overflow: auto; white-space: normal; word-wrap: break-word;'
            ],
            'width'=>'37%',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
        ],

        [
            'class' => '\kartik\grid\ActionColumn',
            'width'=>'5%',
            'template' => '{view}{update}{print} {print2}',
            'buttons'=>[
                'update' => function($url,$model,$key){
                    $btn = Html::button('<span class=\'glyphicon glyphicon-pencil\'></span>', ['value' => Url::to(['update?id='.$model["dv_id"].'&view=edit']), 'title' => 'Edit Disbursement', 'tab-index'=>0 , 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddDisbursement']);
                    return $btn;
                },
                'view' => function($url,$model,$key){
                    $btn = Html::button('<span class=\'glyphicon glyphicon-eye-open\'></span>', ['value' => Url::to(['view?id='.$model["dv_id"]]), 'title' => 'View Disbursement', 'tab-index'=>0 , 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddDisbursement']);
                    return $btn;
                },
                'delete'=>function($url, $model){
                    return Html::a("<span class='glyphicon glyphicon-trash'></span>", $url, [
                        "title"=>"Delete",
                        "aria-label"=>"Delete",
                        "data-pjax"=>"1",
                        "data-method"=>"post",
                        "data-confirm"=>"Are you sure you want to delete?",
                        "class"=>"btn btn-danger"
                    ]);
                },

              /*  'print' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-print"></span>', ['reportdv?id='.$model["dv_id"]], [
                        'class'=>'btn-pdfprint btn btn-warning',
                        'data-pjax'=>"0",
                        'pjax'=>"0",
                        'title'=>'Will open the generated PDF file in a new window'
                    ]);

                },

                */
                'print2' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-print"></span>', ['reportdvfull?id='.$model["dv_id"]], [
                        'class'=>'btn-pdfprint btn btn-primary',
                        'data-pjax'=>"0",
                        'pjax'=>"0",
                        'title'=>'Will open the generated PDF file in a new window'
                    ]);

                },
            ],
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'deleteOptions' => ['label' => '<span class="glyphicon glyphicon-remove"></span>']
        ],

    ];

    echo GridView::widget([
        'id' => 'kv-grid-data',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'columns' => $gridColumns,
        'pjaxSettings' => [
            'neverTimeout'=>false,
            'options' => [
                'enablePushState' => false,
            ],
        ],
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'toolbar' =>
            [
                '{export}',
                '{toggleData}',
                [
                    'content'=>
                        Html::button('Create Disbursement', ['value' => Url::to(['disbursement/create']), 'title' => 'Create Disbursement', 'tab-index'=>0 , 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddDisbursement']) .
                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => false, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
                ],

            ],
        // set export properties
        'export' => [
            'fontAwesome' => true
        ],
        'bordered' => true,
        'striped' => true,
        'condensed' => true,
        'responsive' => true,
        'hover' => true,
        'showPageSummary' => true,
        'panel' => [
            'heading' => 'Data Details',
        ],
        'toolbarContainerOptions' => ['class' => 'btn-toolbar kv-grid-toolbar toolbar-container pull-left'],
        'persistResize' => false,
        'toggleDataOptions' => ['minCount' => 10],
        'exportConfig' => true,
    ]);
    ?>

    <?= pdfprint\Pdfprint::widget([
        'elementClass' => '.btn-pdfprint'
    ]); ?>
</div>
