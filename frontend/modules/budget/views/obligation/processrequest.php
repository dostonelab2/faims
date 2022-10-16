<?php
use yii\helpers\Html;
use yii\helpers\Url;

use kartik\detail\DetailView;
use kartik\editable\Editable;
use kartik\grid\GridView;

use yii\bootstrap\Modal;

$this->title = 'Process Obligation Request';
$this->params['breadcrumbs'][] = ['label' => 'Budget', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $attributes = [
            [
                'group'=>true,
                //'label'=>'<center>LDDAP-ADA</center>',
                'rowOptions'=>['class'=>'info'],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'payee',
                        'label' => 'Payee',
                        //'value'=>'Department of Science and Technology IX',
                        'valueColOptions'=>['style'=>'width:50%'],
                        'labelColOptions'=>['style'=>'width:10%'],
                    ],
                    [
                        'attribute'=>'address',
                        'label'=>'Address',
                        //'value'=>'MDS-GSB Branch/Account No.:',
                        'valueColOptions'=>['style'=>'width:30%'],
                        'labelColOptions'=>['style'=>'width:10%'],
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'particulars',
                        'label'=>'Particulars',
                        //'value' => '',
                        'valueColOptions'=>['style'=>'width:50%'],
                        'labelColOptions'=>['style'=>'width:10%'],
                        
                    ],
                    [
                        'attribute'=>'amount',
                        'label'=>'Amount',
                        'value'=>Yii::$app->formatter->asDecimal($modelObligationRequest->amount, 2),

                        'valueColOptions'=>['style'=>'width:30%'],
                        'labelColOptions'=>['style'=>'width:10%'],
                    ],
                ],
            ],
        ];?>
    <?= DetailView::widget([
            'model' => $modelObligationRequest,
            'mode'=>DetailView::MODE_VIEW,
            /*'deleteOptions'=>[ // your ajax delete parameters
                'params' => ['id' => 1000, 'kvdelete'=>true],
            ],*/
            'container' => ['id'=>'kv-demo'],
            //'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
            
            'buttons1' => '', //hides buttons on detail view
            'attributes' => $attributes,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            'panel' => [
                //'type' => 'Primary', 
                'heading'=>'Obligation Details',
                'type'=>DetailView::TYPE_PRIMARY,
                //'footer' => '<div class="text-center text-muted">This is a sample footer message for the detail view.</div>'
            ],
            
        ]); ?>
        
    <?php
        $gridColumns = [
                [
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '20px',
                    'header' => '',
                    'headerOptions' => ['style' => 'text-align: center; background-color: #f7ab78'],
                    //'mergeHeader' => true,
                ],
                'obligation_id',
                'expenditure_object_id ',
                'name',
                
                'amount',
                //'alobs_id',
                //'expenditure_object_id',
        ];
    ?>
    <?= GridView::widget([
                'id' => 'lddap-ada-items',
                'dataProvider' => $obligationDetailsDataProvider,
                //'filterModel' => $searchModel,
                'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => true, // pjax is set to always true for this demo
                // set left panel buttons
                'panel' => [
                    'heading'=>'<h3 class="panel-title">CREDITORS</h3>',
                    'type'=>'primary',
                ],
                // set right toolbar buttons
                'toolbar' => 
                                [
                                    [
                                        'content'=>
                                            Html::button('Add Creditors  <i class="glyphicon glyphicon-list"></i>', ['value' => Url::to(['lddapadaitem/additems', 'id'=>$model->lddapada_id]), 'title' => 'Creditor', 'class' => 'btn btn-success', 'style'=>'margin-right: 6px; display: "";', 'id'=>'buttonAddCreditor'])
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