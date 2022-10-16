<?php
use common\modules\pdfprint;
use yii\helpers\Html;
use kartik\grid\GridView;

use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel common\models\procurement\ObligationrequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$BaseURL = $GLOBALS['frontend_base_uri'];
$this->registerJsFile($BaseURL.'js/procurement/obligationrequest/ajax-modal-popup.js');
$this->registerJsFile($BaseURL.'js/angular.min.js');
$this->registerJsFile($BaseURL.'js/ui-bootstrap-tpls-0.10.0.min.js');
$this->registerJsFile($BaseURL.'js/jquery.tabletojson.js');
$this->registerJsFile($BaseURL.'js/app.js');
$this->registerJsFile($BaseURL.'js/custom.js');



$this->title = 'Obligation Request Module';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="obligationrequest-index">


    <?php
    //Modal
    Modal::begin([
        'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
        'id' => 'modalObligation',
        'size' => 'modal-lg',
        'options'=> [
             'tabindex'=>false,
        ]
    ]);
    echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
    Modal::end();
    ?>


    <h1 class="centered" style="margin-bottom: 0px;"><i class="fa fa-sitemap"></i> Obligation Request</h1>

    <h5><a id="startButton"  href="javascript:void(0);"><img src="<?= $BaseURL;?>images\help.png" height="52" width="98" style="padding: 10px;"></a></h5>

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
            'width' => '0.5%',
            'vAlign' => 'top',
            'header' => '',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
        ],

        [

            'attribute'=>'os_no',
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

            'attribute'=>'amount',
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
            'width'=>'36%',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
        ],

        [
            'class' => '\kartik\grid\ActionColumn',
            'width'=>'6%',
            'template' => '{view}{update}{print} {print2}',
            'buttons'=>[
            'update' => function($url,$model,$key){
            $btn = "<h5 style='display: inline-block;margin:0px;' data-step='3' data-intro='Click here to update Obligation Request'><span>".Html::button('<span class=\'glyphicon glyphicon-pencil\'></span>', ['value' => Url::to(['update?id='.$model["obligation_request_id"].'&view=edit']), 'title' => 'Edit Obligation Request', 'tab-index'=>0 , 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddObligation'])."</span></h5>";
            return $btn;
            },
            'view' => function($url,$model,$key){
            $btn ="<h5 style='display: inline-block;margin:0px;' data-step='2' data-intro='Click here to view Obligation Request'><span>". Html::button('<span class=\'glyphicon glyphicon-eye-open\'></span>', ['value' => Url::to(['view?id='.$model["obligation_request_id"]]), 'title' => 'View Obligation Request', 'tab-index'=>0 , 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddObligation'])."</span></h5>";
            return $btn;
            },
            'delete'=>function($url, $model){
                return "<h5 style='display: inline-block;margin:0px;' data-step='4' data-intro='Click here to delete Obligation Request'><span>".Html::a("<span class='glyphicon glyphicon-trash'></span>", $url, [
                "title"=>"Delete",
                 "aria-label"=>"Delete",
                 "data-pjax"=>"1",
                 "data-method"=>"post",
                 "data-confirm"=>"Are you sure you want to delete?",
                 "class"=>"btn btn-danger"
                  ])."</span></h5>";
            },
             'print2' => function($url,$model,$key){
                return "<h5 style='display: inline-block;margin:0px;' data-step='6' data-intro='Click here to print Obligation Request'><span>".Html::a('<span class="glyphicon glyphicon-print"></span>', ['reportobfull?id='.$model["obligation_request_id"]], [
                     'class'=>'btn-pdfprint btn btn-warning',
                     'data-pjax'=>"0",
                     'pjax'=>"0",
                     'title'=>'Will open the generated PDF file in a new window'
                 ])."</span></h5>";
                    //$btn = Html::submitButton('<span class=\'glyphicon glyphicon-print\'></span>', ['value' => Url::to(['reportob?id='.$model["obligation_request_id"]]), 'title' => 'Print Obligation Request', 'tab-index'=>0 , 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;']);
                    //return $btn;
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
                           "<h5 style='display: inline-block;margin:0px;' data-step='1' data-intro='Click here to create Obligation Request'><span>".Html::button('Create Obligation Request', ['value' => Url::to(['obligationrequest/create']), 'title' => 'Create Obligation Request', 'tab-index'=>0 , 'class' => 'btn btn-success', 'style'=>'margin-right: 6px;', 'id'=>'buttonAddObligation']) .
                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => false, 'class' => 'btn btn-default', 'title'=>'Reset Grid']). '</span></h5>'
                    ,
                ],
            ],
        // set export properties
        'export' => [
            'fontAwesome' => true
        ],
        'toolbarContainerOptions' => ['class' => 'btn-toolbar kv-grid-toolbar toolbar-container pull-left'],
        'bordered' => true,
        'striped' => true,
        'condensed' => true,
        'responsive' => true,
        'hover' => true,
        'showPageSummary' => true,
        'panel' => [
            'heading' => '',
        ],
        'persistResize' => false,
        'toggleDataOptions' => ['minCount' => 10],
        'exportConfig' => true,
    ]);
    ?>
    <?= pdfprint\Pdfprint::widget([
        'elementClass' => '.btn-pdfprint'
    ]); ?>
</div>

<script type="text/javascript">
    document.getElementById('startButton').onclick = function() {
        introJs().setOption('doneLabel', 'Next page').start().oncomplete(function() {
            $("#buttonAddObligation").click();
        });
    };
</script>