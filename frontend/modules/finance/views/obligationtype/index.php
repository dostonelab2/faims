<?php

use kartik\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\editable\Editable; 
use kartik\grid\GridView;
use yii\bootstrap\Modal;

use common\models\finance\Dv;
use common\models\finance\Os;
use common\models\finance\Request;
use common\models\sec\Blockchain;

/* @var $this yii\web\View */
/* @var $searchModel common\models\finance\ObligationtypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Obligation Types';
$this->params['breadcrumbs'][] = $this->title;

// Modal Create Request
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalObligationtype',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();

Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalContainer',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();
?>
<div class="obligationtype-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php
        echo GridView::widget([
            'id' => 'request',
            'dataProvider' => $dataProvider,
            'columns' => [
                            [
                                'attribute'=>'name',
                                'headerOptions' => ['style' => 'text-align: left; padding-left: 10px;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: center; font-weight: bold;'],
                                'width'=>'120px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->name;
                                }
                            ],
                            [
                                'attribute'=>'project_id',
                                'header'=>'OS Number',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align:middle; text-align: center;'],
                                'width'=>'120px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return ($model->type_id == 1) ? Os::find()->orderBy(['os_id'=>SORT_DESC])->one()->os_number : '-';
                                },
                            ],
                            [   
                                'attribute'=>'project_id',
                                'header' => 'Skip OS',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                                'format' => 'raw',
                                'width'=>'80px',

                                'value'=>function ($model, $key, $index, $widget) { 
                                    return ($model->type_id == 1) ? 
                                    Html::button('<i class="glyphicon glyphicon-forward"></i>', ['value' => Url::to(['obligationtype/skipos', 'id'=>$model->type_id]), 'title' => Yii::t('app', "Skip OS Number"), 'class' => 'btn btn-success', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonSkipOS']) 
                                    : '';
                                },
                            ],
                            [
                                'attribute'=>'fund_category_id',
                                'header'=>'DV Number',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'vertical-align: middle; text-align: center; padding-right: 20px; font-weight: bold;'],
                                'width'=>'200px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return Dv::find()->where(['obligation_type_id' => $model->type_id])->orderBy(['dv_id'=>SORT_DESC])->one()->dv_number;
                                },
                            ],
                            [   
                                'attribute'=>'project_id',
                                'header' => 'Skip DV',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                                'format' => 'raw',
                                'width'=>'80px',

                                'value'=>function ($model, $key, $index, $widget) { 
                                    return Html::button('<i class="glyphicon glyphicon-fast-forward"></i>', ['value' => Url::to(['obligationtype/skipdv', 'type_id'=>$model->type_id]), 'title' => Yii::t('app', "Skip DV Number"), 'class' => 'btn btn-success', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonSkipDV']);
                                },
                            ],
                            [   
                                'attribute'=>'project_id',
                                'header' => 'Add Payroll',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                                'format' => 'raw',
                                'width'=>'80px',

                                'value'=>function ($model, $key, $index, $widget) { 
                                    return Html::button('<i class="glyphicon glyphicon-fast-forward"></i>', ['value' => Url::to(['obligationtype/payroll', 'type_id'=>$model->type_id]), 'title' => Yii::t('app', "OSDV for Payroll"), 'class' => 'btn btn-info', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonSkipDV']);
                                },
                            ],
                            [
                                'class' => kartik\grid\ActionColumn::className(),
                                'template' => '{view}',
                                'buttons' => [

                                    'view' => function ($url, $model){
                                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value' => '/finance/obligationtype/view?id=' . $model->type_id,'onclick'=>'location.href=this.value', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View Obligation Type")]);
                                    },
                                ],
                            ],
                    ],
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            'panel' => [
                    'heading' => '',
                    'type' => GridView::TYPE_PRIMARY,
                    'before'=>Html::button('New Obligation Type', ['value' => Url::to(['obligationtype/create']), 'title' => 'Request', 'class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonCreateObligationtype']),
                    'after'=>false,
                ],
            // set your toolbar
            'toolbar' => 
                        [
                            [
                                'content'=>'',
                                    /*Html::button('PENDING', ['title' => 'Approved', 'class' => 'btn btn-warning', 'style'=>'width: 90px; margin-right: 6px;']) .    
                                    Html::button('SUBMITTED', ['title' => 'Approved', 'class' => 'btn btn-primary', 'style'=>'width: 90px; margin-right: 6px;']) .
                                    Html::button('APPROVED', ['title' => 'Approved', 'class' => 'btn btn-success', 'style'=>'width: 90px; margin-right: 6px;'])*/
                            ],
                            //'{export}',
                            //'{toggleData}'
                        ],
            
            'toggleDataOptions' => ['minCount' => 10],
            //'exportConfig' => $exportConfig,
            'itemLabelSingle' => 'item',
            'itemLabelPlural' => 'items'
        ]);
    

        ?>
</div>


<pre>
<?php 
    /* foreach($blockchain as $block){
        $certified = Blockchain::find()->where('index_id =:index_id AND scope =:scope AND SUBSTR(`data`, -2, 2) =:status',[':index_id'=> $block->index_id, ':scope'=> $block->scope, ':status'=>65])->one();
        
        $found2 = 'none';
        
        if(!$certified){
            $approved = Blockchain::find()->where('index_id =:index_id AND scope =:scope AND SUBSTR(`data`, -2, 2) =:status',[':index_id'=> $block->index_id, ':scope'=> $block->scope, ':status'=>70])->one();
            
            if($approved){
                echo $block->index_id." ----------- ".$block->scope." ----------- ".$block->data." ----------- ".$approved->data."<br/>";
                
                $arr = explode(":",$block->data);
                    
                //$index = $block->index_id;
                //$scope = 'Osdv';
                //$data = $block->index_id.':'.$arr[1].':'.$arr[2].':'.$arr[3].':1,2,3:65';
                //Blockchain::createBlock($index, $scope, $data);
            }
        }
    }*/ 
    //.$request->creditor->name.

    /*foreach($requests as $request){
        $chain = Blockchain::find()->where('index_id =:index_id AND scope =:scope',[':index_id'=> $request->request_id, ':scope'=> 'request'])->all();
        //$chain = Blockchain::find()->where('index_id =:index_id AND scope =:scope AND SUBSTR(`data`, -2, 2) =:status',[':index_id'=> $request->request_id, ':scope'=> 'request', ':status'=>40])->one();
        $data = "";
        if($chain){
            foreach($chain as $c){
                $data .= substr($c->data, -2, 2).':';
            }
            echo $request->request_number." ----------- ".$data." ----------- ".$request->status_id."<br/>";
        }
    }*/


    /* OS Prining Fix Here */
    /*$request = Request::findOne(2220);
    $status = [
        '0' => 20,
        '1' => 30,
        '2' => 40
    ];
    $text = $request->request_id.' : ';

    for($i=0;$i<=3;$i++){
        $exist = Blockchain::find()->where('index_id =:index_id AND scope =:scope AND SUBSTR(`data`, -2, 2) =:status',[':index_id'=> $request->request_id, ':scope'=> 'Request', ':status'=>$status[$i]])->one();
        
        //$text .= $exist ? $status[$i] : 'none';
        
        if(!$exist){
            Blockchain::createRequestBlockchain($request->request_id, $status[$i]);
            $text .= 'none';
        }else{
            $text .= $status[$i];
        }
        $text .= " : ";
        //echo Blockchain::createRequestBlockchain($request->request_id, $status[$i]);
    }
    echo $text;*/
?>
</pre>