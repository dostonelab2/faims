<?php
use kartik\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

use kartik\datecontrol\DateControl;
use kartik\detail\DetailView;
use kartik\editable\Editable; 
use kartik\grid\GridView;

use yii\bootstrap\Modal;

use common\models\cashier\Lddapada;
/* @var $this yii\web\View */
/* @var $searchModel common\models\cashier\LddapadaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'LDDAP-ADA';
$this->params['breadcrumbs'][] = $this->title;

// Modal Create LDDAP-ADA
Modal::begin([
    'header' => '<h4 id="modalHeader" style="color: #ffffff"></h4>',
    'id' => 'modalLddapada',
    'size' => 'modal-md',
    'options'=> [
             'tabindex'=>false,
        ],
]);

echo "<div id='modalContent'><div style='text-align:center'><img src='/images/loading.gif'></div></div>";
Modal::end();
?>

<div class="lddapada-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <!--?= Html::a('Create', ['create'], ['class' => 'btn btn-success', 'id' => 'buttonCreateLddapada']) ?-->
    </p>
<?php Pjax::begin(); ?>
      <?php
        echo GridView::widget([
            'id' => 'lddap-ada',
            'dataProvider' => $dataProvider,
            'columns' => [
                            [
                                'attribute'=>'batch_number',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'width'=>'20%',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    //return Html::a(Yii::t('app','label'), ['lddapada/view', 'id'=>$model->lddapada_id], ['class'=>'pull-right', 'style' => 'padding-right:10px;']);
                                    return '<b>'.Html::a($model->batch_number, ['lddapada/view', 'id'=>$model->lddapada_id], ['style' => 'font-size: medium;', 'target' => '_blank', 'data-pjax'=>0]).'</b><br/>'.date('Y-m-d',strtotime($model->batch_date));
                                },
                            ],
                            [
                                'attribute'=>'type_id',
                                'header'=>'Fund Source',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'width'=>'20%',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->type_id ? $model->fundsource->name : '-';
                                },
                            ],
                            [
                                'attribute'=>'batch_date',
                                'header'=>'Creditors (amount)',
                                'contentOptions' => ['style' => 'padding-left: 25px;'],
                                'width'=>'250px',
                                'format'=>'raw',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    $html = "";
                                    foreach($model->lddapadaItems as $item){
                                        $html .= $item->name.' (<b>'.number_format($item->osdv->getNetamount(),2).'</b>)<br/>';
                                    }
                                    return $html;
                                }
                            ],
                            [
                                'attribute'=>'type_id',
                                'header'=>'Total',
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: right; padding-right: 10px; font-weight: bold;'],
                                'width'=>'20%',
                                'format'=>['decimal',2],
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return $model->getTotal();
                                },
                            ],
                            [
                                'class' => kartik\grid\ActionColumn::className(),
                                'template' => '{view}',
                                'buttons' => [

                                    'view' => function ($url, $model){
                                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value' => '/cashier/lddapada/view?id=' . $model->lddapada_id,'onclick'=>'location.href=this.value', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View LDDAP-ADA")]);
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
                    'before'=>Html::button('New LDDAP-ADA ( <b>'.$count.'</b> )', ['value' => Url::to(['lddapada/create']), 'title' => $new_items, 'data-toggle' => 'tooltip',  'data-placement' => 'right','class' => 'btn btn-info', 'style'=>'margin-right: 6px;', 'id'=>'buttonCreateLddapada']),
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
        <?php Pjax::end(); ?></div>
<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>